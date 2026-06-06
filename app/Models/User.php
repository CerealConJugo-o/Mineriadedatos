<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos que se pueden guardar masivamente
     */
protected $fillable = [
    'name',
    'email',
    'password',
    'role_id', // <--- ¡Importante! Sin esto, el Seeder ignorará la columna
];
public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    /**
     * Campos que deben ocultarse cuando se convierten a array o JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting automático para algunos valores
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
// En app/Models/User.php

public function hasRole($roles)
{
    // 1. Si nos envían un solo texto (ej: 'gerente'), lo convertimos en lista
    if (is_string($roles)) {
        $roles = [$roles];
    }

    // 2. Verificamos si el rol del usuario está DENTRO de esa lista
    // (Esto permite preguntar: "¿Eres Vendedor O Gerente?")
    return $this->role && in_array($this->role->nombre, $roles);
}
}

