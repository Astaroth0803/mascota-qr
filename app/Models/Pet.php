<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'especie',
        'raza',
        'edad',
        'sexo',
        'vaccine_file', // Añadir este campo
    ];
    protected static function booted()

{
    static::deleted(function ($pet) {
        if ($pet->vaccine_file) {
            Storage::disk('public')->delete($pet->vaccine_file);
        }
    });
}   
}
// En el modelo Pet

