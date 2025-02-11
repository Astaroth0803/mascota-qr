<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'sexo',
        'nombre_owner',
        'apellido_owner',
        'telefono_owner',
        'correo_owner',
        'id_pago_yappy',
        'vaccine_file',
    ];

    // Relación con el modelo Payment (uno a uno)
    public function payment()
    {
        return $this->hasOne(Payment::class);  // Esto asocia un pago a cada mascota
    }
    public function user()
{
    return $this->belongsTo(User::class);
}
}
