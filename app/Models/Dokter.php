<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dokter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rumah_sakit_id',
        'spesialisasi_id',
        'poli_id',
        'email',
        'password',
        'nama_dokter',
        'gelar',
        'foto_url',
        'no_str',
        'pengalaman_tahun',
        'status_aktif',
        'gaji_pokok',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pengalaman_tahun' => 'integer',
            'status_aktif' => 'string',
            'gaji_pokok' => 'decimal:2',
        ];
    }

    /**
     * Get the hospital that the doctor belongs to.
     */
    public function rumahSakit(): BelongsTo
    {
        return $this->belongsTo(RumahSakit::class);
    }

    /**
     * Get the specialization of the doctor.
     */
    public function spesialisasi(): BelongsTo
    {
        return $this->belongsTo(Spesialisasi::class);
    }

    /**
     * Get the poli of the doctor.
     */
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }

    /**
     * Get the schedules for the doctor.
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(JadwalDokter::class);
    }

    /**
     * Get the salary records for the doctor.
     */
    public function gaji(): HasMany
    {
        return $this->hasMany(GajiDokter::class);
    }
    
    /**
     * Get the antrian records for the doctor.
     */
    public function antrian(): HasMany
    {
        return $this->hasMany(Antrian::class);
    }
}