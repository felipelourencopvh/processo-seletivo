<?php

declare(strict_types=1);

namespace App\Filament\Auth;

use App\Services\Siga\SigaOidc;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\View;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

/**
 * "Meu perfil" dos painéis /admin e /app, dentro do layout do painel (menu e barra superior),
 * no lugar do cartão solto padrão do Filament. Com o SIGA configurado a senha é a do login único,
 * então a troca de senha daqui some e a tela aponta para o SIGA.
 */
class MeuPerfil extends EditProfile
{
    protected static string $view = 'filament.auth.meu-perfil';

    /**
     * No painel da comissão (/app, com tenant por processo) a rota do perfil não tem processo na URL,
     * mas o layout completo (menu, links) precisa de um. Usa o primeiro processo a que a pessoa tem acesso.
     */
    public function boot(): void
    {
        $panel = Filament::getCurrentPanel();

        if ($panel?->hasTenancy() && ! Filament::getTenant()) {
            $tenant = Filament::getUserDefaultTenant($this->getUser())
                ?? collect(Filament::getUserTenants($this->getUser()))->first();

            if ($tenant) {
                Filament::setTenant($tenant);
            }
        }
    }

    public static function isSimple(): bool
    {
        return false;
    }

    public static function getLabel(): string
    {
        return 'Meu perfil';
    }

    public function getTitle(): string
    {
        return 'Meu perfil';
    }

    public function form(Form $form): Form
    {
        $siga = app(SigaOidc::class)->configurado();

        return $form->inlineLabel(false)->schema([
            Grid::make(['default' => 1, 'lg' => 3])->schema([
                Group::make([
                    Section::make('Seus dados')
                        ->description('Nome e e-mail que aparecem para a comissão e nos registros do sistema.')
                        ->icon('heroicon-o-identification')
                        ->columns(2)
                        ->schema([
                            FileUpload::make('avatar_path')
                                ->label('Foto')
                                ->helperText('JPG, PNG ou WEBP de até 2 MB. Dá para recortar depois de escolher.')
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->disk('public')
                                ->directory('avatares')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(2048)
                                ->columnSpanFull(),
                            $this->getNameFormComponent()->label('Nome completo')->columnSpanFull(),
                            $this->getEmailFormComponent()->label('E-mail'),
                            Placeholder::make('cpf')
                                ->label('CPF')
                                ->content(fn (): string => $this->cpfFormatado() ?? 'não informado'),
                        ]),

                    $siga
                        ? Section::make('Acesso pelo SIGA')
                            ->icon('heroicon-o-key')
                            ->schema([
                                Placeholder::make('acesso')
                                    ->hiddenLabel()
                                    ->content(new HtmlString(
                                        'Você entra com o login único do STX Gov (SIGA). A senha é a mesma do SIGA: '
                                        . 'para trocá-la, use a tela de senha de lá. '
                                        . '<a href="' . e(rtrim((string) config('services.siga.url'), '/')) . '" target="_blank" rel="noopener" '
                                        . 'class="font-semibold text-primary-600 hover:underline">Abrir o SIGA</a>'
                                    )),
                            ])
                        : Section::make('Trocar senha')
                            ->description('Deixe em branco para manter a senha atual.')
                            ->icon('heroicon-o-key')
                            ->columns(2)
                            ->schema([
                                $this->getPasswordFormComponent()->label('Nova senha'),
                                $this->getPasswordConfirmationFormComponent()->label('Confirme a nova senha'),
                            ]),
                ])->columnSpan(['lg' => 2]),

                View::make('filament.auth.meu-perfil-resumo')->columnSpan(['lg' => 1]),
            ]),
        ]);
    }

    /** Troca de foto: apaga o arquivo anterior para não acumular fotos antigas no disco. */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $anterior = $record->getOriginal('avatar_path');

        $record = parent::handleRecordUpdate($record, $data);

        if ($anterior && $anterior !== $record->avatar_path) {
            Storage::disk('public')->delete($anterior);
        }

        return $record;
    }

    public function cpfFormatado(): ?string
    {
        $cpf = preg_replace('/\D/', '', (string) $this->getUser()->cpf);

        return strlen($cpf) === 11 ? vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($cpf)) : null;
    }
}
