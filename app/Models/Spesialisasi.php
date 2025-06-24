<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spesialisasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spesialisasi';
    
    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_spesialisasi',
        'deskripsi',
    ];

    /**
     * Get the doctors for the specialization.
     */
    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class);
    }
    
    /**
     * Get the poli for the specialization.
     */
    public function poli(): HasMany
    {
        return $this->hasMany(Poli::class);
    }
}