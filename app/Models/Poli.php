<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'poli';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'spesialisasi_id',
        'nama_poli',
        'deskripsi',
        'lokasi',
        'status_aktif',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_aktif' => 'string',
        ];
    }

    /**
     * Get the specialization that owns the poli.
     */
    public function spesialisasi(): BelongsTo
    {
        return $this->belongsTo(Spesialisasi::class);
    }

    /**
     * Get the doctors in this poli.
     */
    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class);
    }

    /**
     * Get the antrian records for the poli.
     */
    public function antrian(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}