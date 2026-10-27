<?php

namespace App\Mail;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\MessageConverter;
use Throwable;

/**
 * E-mail pela Central de avisos do SIGA (ADR-016): o servidor de envio é um só para o ecossistema, configurado no
 * SIGA › Central de avisos. O sistema entrega a mensagem ao SIGA com a chave de provisionamento que já tem
 * (SIGA_PROVISIONAMENTO_TOKEN). Falha de envio não derruba a tela de quem enviou: fica no log daqui e no registro da
 * Central. Com MAIL_MAILER=siga.
 */
class SigaTransport extends AbstractTransport
{
    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $para  = array_map(fn (Address $a): string => $a->getAddress(), [...$email->getTo(), ...$email->getCc(), ...$email->getBcc()]);
        $api   = rtrim((string) config('services.siga.api_interno'), '/');
        $token = (string) config('services.siga.provisionamento_token');
        if ($api === '' || $token === '') {
            Log::warning('E-mail não enviado: o sistema não está ligado ao SIGA (SIGA_API_INTERNO e SIGA_PROVISIONAMENTO_TOKEN).', ['assunto' => $email->getSubject()]);

            return;
        }
        $resposta = $email->getReplyTo()[0] ?? null;

        try {
            $r = Http::timeout(30)->acceptJson()->withToken($token)->post("{$api}/siga/api/avisos/email", [
                'para'          => $para,
                'assunto'       => (string) $email->getSubject(),
                'texto'         => (string) ($email->getTextBody() ?? strip_tags((string) $email->getHtmlBody())),
                'html'          => $email->getHtmlBody(),
                'responderPara' => $resposta?->getAddress(),
                'anexos'        => array_values(array_map(fn ($anexo): array => [
                    'nome'     => $anexo->getFilename() ?? 'anexo',
                    'tipo'     => $anexo->getContentType(),
                    'conteudo' => base64_encode($anexo->getBody()),
                ], $email->getAttachments())),
            ]);
            if ( ! $r->successful() || $r->json('situacao') !== 'enviado') {
                Log::warning('E-mail não saiu pela Central de avisos do SIGA.', ['assunto' => $email->getSubject(), 'status' => $r->status(), 'erro' => $r->json('erro') ?? $r->json('detail')]);
            }
        } catch (Throwable $e) {
            Log::error('Central de avisos do SIGA fora do ar: e-mail não enviado.', ['assunto' => $email->getSubject(), 'erro' => $e->getMessage()]);
        }
    }

    public function __toString(): string
    {
        return 'siga';
    }
}
