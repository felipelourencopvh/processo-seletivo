<?php

namespace Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/** E-mail pela Central de avisos do SIGA (MAIL_MAILER=siga): a mensagem vai para o SIGA, que envia pelo SMTP de lá. */
class CentralAvisosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['mail.default' => 'siga', 'services.siga.api_interno' => 'http://sgpm-api:3333/api/v1', 'services.siga.provisionamento_token' => 'siga_ci_456']);
    }

    private function mensagem(): Mailable
    {
        return new class () extends Mailable {
            public function build(): static
            {
                return $this->subject('Documento encaminhado')->html('<p>Olá, <b>Ana</b></p>')->replyTo('controle@prefeitura.gov.br')
                    ->attach(Attachment::fromData(fn () => 'conteúdo', 'parecer.pdf')->withMime('application/pdf'));
            }
        };
    }

    public function test_entrega_a_mensagem_ao_siga_com_a_chave_do_sistema(): void
    {
        Http::fake(['sgpm-api:3333/*' => Http::response(['id' => 1, 'situacao' => 'enviado', 'erro' => null])]);

        Mail::to('ana@exemplo.com')->cc('chefe@exemplo.com')->send($this->mensagem());

        Http::assertSent(fn (Request $r): bool => $r->url() === 'http://sgpm-api:3333/api/v1/siga/api/avisos/email'
            && $r->hasHeader('Authorization', 'Bearer siga_ci_456')
            && $r['para'] === ['ana@exemplo.com', 'chefe@exemplo.com']
            && $r['assunto'] === 'Documento encaminhado'
            && str_contains($r['html'], '<b>Ana</b>')
            && $r['responderPara'] === 'controle@prefeitura.gov.br'
            && $r['anexos'][0]['nome'] === 'parecer.pdf'
            && base64_decode($r['anexos'][0]['conteudo']) === 'conteúdo');
    }

    public function test_siga_fora_do_ar_nao_derruba_quem_enviou(): void
    {
        Http::fake(fn () => throw new ConnectionException('fora do ar'));

        Mail::to('ana@exemplo.com')->send($this->mensagem());

        $this->assertTrue(true);
    }
}
