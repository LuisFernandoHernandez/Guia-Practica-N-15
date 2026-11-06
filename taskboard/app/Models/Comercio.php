<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comercio extends Model
{
    use HasFactory;

    protected $table = 'comercios';

    protected $fillable = [
        'user_id',
        'nombre_comercio',
        'rubro',
        'telefono',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
