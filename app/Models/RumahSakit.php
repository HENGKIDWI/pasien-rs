<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RumahSakit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rumah_sakit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_rs',
        'alamat',
        'no_telepon',
        'email',
        'logo_url',
        'jam_operasional',
    ];

    /**
     * Get the doctors for the hospital.
     */
    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class);
    }

    /**
     * Get the announcements for the hospital.
     */
    public function pengumuman(): HasMany
    {
        return $this->hasMany(Pengumuman::class);
    }

    /**
     * Get the antrian records for the hospital.
     */
    public function antrian(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}