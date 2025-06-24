<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Antrian extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'antrian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pengunjung_id',
        'dokter_id',
        'rumah_sakit_id',
        'poli_id',
        'nomor_antrian',
        'tanggal_kunjungan',
        'jam_kunjungan',
        'keluhan',
        'status_antrian',
        'estimasi_waktu_tunggu',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_kunjungan' => 'date',
            'jam_kunjungan' => 'datetime:H:i:s',
            'estimasi_waktu_tunggu' => 'integer',
        ];
    }
    
    /**
     * Get the pengunjung that owns the antrian.
     */
    public function pengunjung(): BelongsTo
    {
        return $this->belongsTo(Pengunjung::class);
    }

    /**
     * Get the doctor for the antrian.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    /**
     * Get the hospital for the antrian.
     */
    public function rumahSakit(): BelongsTo
    {
        return $this->belongsTo(RumahSakit::class);
    }
    
    /**
     * Get the poli for the antrian.
     */
    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }
}