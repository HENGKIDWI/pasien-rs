<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalDokter extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jadwal_dokter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota_pasien',
        'status_jadwal',
        'biaya_konsultasi',
    ];
    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jam_mulai' => 'datetime:H:i:s',
            'jam_selesai' => 'datetime:H:i:s',
            'kuota_pasien' => 'integer',
            'biaya_konsultasi' => 'decimal:2',
        ];
    }

    /**
     * Get the doctor that owns the schedule.
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }
}