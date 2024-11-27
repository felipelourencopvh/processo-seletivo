<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProcessoStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Set;
use Filament\Models\Contracts\HasName;
use Filament\Support\Enums\Alignment;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Processo extends Model implements HasName
{
    use LogsActivity;
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'titulo',
        'slug',
        'descricao',
        'data_abertura',
        'data_resultado',
        'data_suspensao_parcial',
        'data_suspensao_integral',
        'data_recurso_inicio',
        'data_recurso_fim',
        'data_liberacao_recurso',
        'secretaria_id',
    ];

    public static function getForm()
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tabs\Tab::make('Informações Gerais')
                        ->schema([
                            Section::make([
                                TextInput::make('titulo')->label('Título')->live(true)
                                    ->afterStateUpdated(function (
                                        Set $set,
                                        ?string $state,
                                    ) {
                                        return $set('slug', Str::slug($state));
                                    })->required()->unique(ignoreRecord: true)->maxLength(255),

                                TextInput::make('slug')->maxLength(255)->unique(ignoreRecord: true)->label('Slug'),

                                TiptapEditor::make('descricao')->label('Descrição')->profile('default')->extraInputAttributes(
                                    ['style' => 'min-height: 12rem;'],
                                ),

                                DateTimePicker::make('data_abertura')->label('Data de Abertura')->seconds(false),

                                DatePicker::make('data_resultado')->label('Data do Resultado'),

                                DateTimePicker::make('data_suspensao_parcial')->label('Data da Suspensao Parcial')->seconds(false),

                                DateTimePicker::make('data_suspensao_integral')->label('Data da Suspensao Integral')->seconds(false),

                                DateTimePicker::make('data_recurso_inicio')->label('Início do Período de Recurso')->seconds(false),
                                DateTimePicker::make('data_recurso_fim')->label('Fim do Período de Recurso')->seconds(false),
                                DateTimePicker::make('data_liberacao_recurso')->label('Data de Liberação do Recurso')->seconds(false),

                                Select::make('secretaria_id')
                                    ->label('Secretaria')
                                    ->options(Secretaria::all()->pluck('sigla', 'id'))
                                    ->required()
                                    ->searchable(),
                            ]),
                            Section::make([
                                Placeholder::make('created_at')
                                    ->label('Criado Em')
                                    ->content(fn (?Processo $record): string => $record?->created_at?->diffForHumans() ?? '-'),

                                Placeholder::make('updated_at')
                                    ->label('Alterado Em')
                                    ->content(fn (?Processo $record): string => $record?->updated_at?->diffForHumans() ?? '-'),
                            ]),
                        ]),
                    Tabs\Tab::make('Anexos')
                        ->schema([
                            Repeater::make('anexos')->label('Anexos')
                                ->relationship()
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('documento')
                                        ->maxSize(1024 * 100)
                                        ->acceptedFileTypes(['application/pdf'])->downloadable(),
                                    TextInput::make('nome')
                                        ->required()
                                        ->live(onBlur: true),
                                    Select::make('tipo')->label('Tipo de Anexo')
                                        ->options([
                                            'editais' => 'Editais e Erratas',
                                            'resultados' => 'Resultados, Resoluções e Editais de Convocação',
                                            'anexos' => 'Anexos',
                                        ])
                                        ->required(),
                                    DatePicker::make('data_publicacao')->label('Data de Publicação')->required(),
                                ])
                                ->columns(1)
                                ->itemLabel(fn (array $state): ?string => $state['nome'] ?? null)
                                ->defaultItems(0)
                                ->addActionAlignment(Alignment::Start)
                                ->addActionLabel('+ Anexos'),
                        ]),
                ])->persistTabInQueryString()->columnSpanFull(),

        ];
    }

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(Secretaria::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function membros(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function scopeAberto(Builder $query): Builder
    {
        return $query->where('data_abertura', '<=', now())->where('data_suspensao_parcial', '>', now());
    }

    public function scopeEmBreve(Builder $query): Builder
    {
        return $query->where('data_abertura', '>', now());
    }

    public function scopeEmAndamento(Builder $query): Builder
    {
        return $query->where('data_abertura', '<', now())->where('data_suspensao_parcial', '<', now())->where('data_resultado', '>', now());
    }

    public function scopeEncerrado(Builder $query): Builder
    {
        return $query->where('data_resultado', '<', now());
    }

    public function anexos(): HasMany
    {
        return $this->hasMany(ProcessoAnexo::class);
    }

    public function getFilamentName(): string
    {
        return $this->titulo;
    }

    public function cargos(): HasMany
    {
        return $this->hasMany(ProcessoCargo::class);
    }

    public function isDentroPeriodoRecurso(): bool
    {
        if (empty($this->data_recurso_inicio) || empty($this->data_recurso_fim)) {
            return false;
        }
        $hoje = now();

        return $hoje >= $this->data_recurso_inicio and $hoje <= $this->data_recurso_fim;
    }

    public function isRecursoLiberado(): bool
    {
        if (empty($this->data_liberacao_recurso)) {
            return false;
        }

        return $this->data_liberacao_recurso <= now();
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes): ProcessoStatus {
                if ($attributes['data_abertura'] > now()) {
                    return ProcessoStatus::EM_BREVE;
                }

                if ($attributes['data_abertura'] <= now() && $attributes['data_suspensao_parcial'] > now()) {
                    return ProcessoStatus::ABERTO;
                }

                if ($attributes['data_abertura'] < now() && $attributes['data_suspensao_parcial'] < now() && $attributes['data_resultado'] > now()) {
                    return ProcessoStatus::EM_ANDAMENTO;
                }

                return ProcessoStatus::ENCERRADO;
            },
        );
    }

    protected function casts()
    {
        return [
            'data_abertura' => 'datetime',
            'data_resultado' => 'date',
            'data_suspensao_parcial' => 'datetime',
            'data_suspensao_integral' => 'datetime',
            'data_recurso_inicio' => 'datetime',
            'data_recurso_fim' => 'datetime',
            'data_liberacao_recurso' => 'datetime',
        ];
    }
}
