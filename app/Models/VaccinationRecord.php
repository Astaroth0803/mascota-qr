<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'file_path',
        'vaccination_date',
        'vaccine_type',
        'notes',
    ];

    /**
     * Get the pet that owns the vaccination record.
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
