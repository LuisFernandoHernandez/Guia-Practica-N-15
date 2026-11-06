<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transacciones';

    protected $fillable = [
        'comercio_id',
        'monto',
        'cliente_nombre',
        'estado',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    public function comercio(): BelongsTo
    {
        return $this->belongsTo(Comercio::class);
    }

    public function eventos(): HasMany
    {
        return $this->hasMany(EventoTransaccion::class);
    }
}
