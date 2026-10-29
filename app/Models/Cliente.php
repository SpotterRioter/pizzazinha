<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cpf',
        'cpf_hash',
        'email',
        'telefone',
        'endereco',
        'cidade',
        'uf',
        'observacoes',
    ];

    protected $hidden = [
        'cpf',
        'cpf_hash',
    ];

    protected function casts(): array
    {
        return [
            'cpf' => 'encrypted',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Cliente $cliente) {
            $digits = preg_replace('/\D/', '', (string) $cliente->cpf);

            $cliente->cpf_hash = $digits !== ''
                ? hash_hmac('sha256', $digits, (string) config('app.key'))
                : null;
        });
    }

    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Hash determinístico usado para buscas e verificação de duplicidade.
     */
    public static function hashCpf(?string $cpf): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $cpf);

        return $digits !== '' ? hash_hmac('sha256', $digits, (string) config('app.key')) : null;
    }

    public function getCpfMascaradoAttribute(): string
    {
        $digits = preg_replace('/\D/', '', (string) $this->cpf);

        if (strlen($digits) !== 11) {
            return '***.***.***-**';
        }

        return substr($digits, 0, 3).'.***.***-'.substr($digits, 9, 2);
    }

    public function getCpfFormatadoAttribute(): string
    {
        $digits = preg_replace('/\D/', '', (string) $this->cpf);

        if (strlen($digits) !== 11) {
            return $this->cpf ?? '';
        }

        return substr($digits, 0, 3).'.'.substr($digits, 3, 3).'.'.substr($digits, 6, 3).'-'.substr($digits, 9, 2);
    }

    public function getTotalGastoAttribute(): float
    {
        return (float) $this->pedidos()->where('status', '!=', 'cancelado')->sum('valor_total');
    }

    public function scopeBusca(Builder $query, ?string $termo): Builder
    {
        if (! $termo) {
            return $query;
        }

        $like = '%'.trim($termo).'%';
        $cpfHash = static::hashCpf($termo);

        return $query->where(function (Builder $q) use ($like, $cpfHash) {
            $q->where('nome', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('telefone', 'like', $like)
                ->orWhere('cidade', 'like', $like);

            if ($cpfHash) {
                $q->orWhere('cpf_hash', $cpfHash);
            }
        });
    }
}
