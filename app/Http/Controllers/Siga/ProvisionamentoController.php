<?php

declare(strict_types=1);

namespace App\Http\Controllers\Siga;

use App\Http\Controllers\Controller;
use App\Models\Processo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * ADR-016 §5: o SIGA cria o usuário e diz quais perfis ele tem aqui (config/perfis.php). Cada perfil concede um
 * papel do Filament Shield; o de gestor vale nos processos seletivos dados (o "alcance" é o id do processo).
 * A senha daqui fica inutilizável para quem o SIGA cria: a entrada no backoffice é pelo SIGA. O candidato
 * (sem papel) não é afetado: só os papéis do backoffice mudam.
 */
class ProvisionamentoController extends Controller
{
    public function perfis(): JsonResponse
    {
        return response()->json(collect(config('perfis'))->map(fn (array $perfil, string $codigo): array => [
            'codigo'       => $codigo,
            'nome'         => $perfil['nome'],
            'descricao'    => $perfil['descricao'],
            'exigeAlcance' => $perfil['alcance'],
        ])->values());
    }

    public function alcances(): JsonResponse
    {
        return response()->json(Processo::query()->orderByDesc('id')->get(['id', 'titulo'])
            ->map(fn (Processo $p): array => ['codigo' => (string) $p->id, 'nome' => $p->titulo])->values());
    }

    public function consultar(string $cpf): JsonResponse
    {
        return response()->json($this->situacao($this->usuario($cpf)));
    }

    public function aplicar(Request $request, string $cpf): JsonResponse
    {
        $dados = $request->validate([
            'nome'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'max:255'],
            'ativo'             => ['required', 'boolean'],
            'acessos'           => ['present', 'array'],
            'acessos.*.perfil'  => ['required', 'string'],
            'acessos.*.alcance' => ['nullable', 'string'],
        ]);

        $perfis    = config('perfis');
        $problemas = [];
        foreach ($dados['acessos'] as $acesso) {
            $perfil  = $perfis[$acesso['perfil']] ?? null;
            $alcance = $acesso['alcance'] ?? null;
            if ( ! $perfil) {
                $problemas[] = "perfil {$acesso['perfil']} não existe no Recrutamento";
            } elseif ($perfil['alcance'] && ! $alcance) {
                $problemas[] = "o perfil {$perfil['nome']} vale num processo seletivo: informe qual";
            } elseif ($perfil['alcance'] && ! Processo::query()->whereKey($alcance)->exists()) {
                $problemas[] = "processo seletivo {$alcance} não existe no Recrutamento";
            }
        }
        if ($problemas) {
            return response()->json(['detail' => 'Não foi possível gravar no Recrutamento: ' . implode('; ', array_unique($problemas)) . '.'], 422);
        }

        $usuario = $this->usuario($cpf);
        $email   = Str::lower($dados['email']);
        if (User::query()->where('email', $email)->when($usuario, fn ($q) => $q->whereKeyNot($usuario->id))->exists()) {
            return response()->json(['detail' => "O e-mail {$email} já é de outro usuário do Recrutamento."], 422);
        }

        DB::transaction(function () use (&$usuario, $cpf, $dados, $email, $perfis): void {
            $usuario ??= new User(['cpf' => $cpf, 'password' => Hash::make(Str::random(48))]);
            $usuario->fill(['name' => $dados['nome'], 'email' => $email, 'ativo' => $dados['ativo']])->save();

            // Grava exatamente a lista recebida: os papéis do backoffice que não vieram saem.
            $papeis = collect($dados['acessos'])->map(fn (array $a): string => $perfis[$a['perfil']]['papel'])->unique()->values();
            foreach ($papeis as $papel) {
                Role::findOrCreate($papel, 'web');
            }
            $usuario->syncRoles($papeis->all());
            $processos = collect($dados['acessos'])->filter(fn (array $a): bool => $perfis[$a['perfil']]['alcance'])->pluck('alcance')->map(fn ($id): int => (int) $id)->unique()->values();
            $usuario->processos()->sync($processos->all());
        });

        return response()->json($this->situacao($usuario->fresh()));
    }

    private function usuario(string $cpf): ?User
    {
        // O CPF pode estar gravado com ou sem máscara (o cadastro do candidato usa máscara).
        $mascara = preg_replace('/^(\d{3})(\d{3})(\d{3})(\d{2})$/', '$1.$2.$3-$4', $cpf);

        return User::query()->whereIn('cpf', [$cpf, $mascara])->first();
    }

    /** @return array{existe: bool, ativo: bool, acessos: list<array<string, mixed>>} */
    private function situacao(?User $usuario): array
    {
        if ( ! $usuario) {
            return ['existe' => false, 'ativo' => false, 'acessos' => []];
        }
        $acessos = [];
        foreach (config('perfis') as $codigo => $perfil) {
            if ( ! $usuario->hasRole($perfil['papel'])) {
                continue;
            }
            if ($perfil['alcance']) {
                foreach ($usuario->processos()->pluck('processos.id') as $id) {
                    $acessos[] = ['perfil' => $codigo, 'alcance' => (string) $id, 'de' => null, 'ate' => null];
                }
            } else {
                $acessos[] = ['perfil' => $codigo, 'alcance' => null, 'de' => null, 'ate' => null];
            }
        }

        return ['existe' => true, 'ativo' => (bool) $usuario->ativo, 'acessos' => $acessos];
    }
}
