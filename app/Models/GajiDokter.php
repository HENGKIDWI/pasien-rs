<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GajiDokter extends Model
{
    use HasFactory;

    protected $table = 'gaji_dokter';
    
    // Karena tabel ini menggunakan composite primary key (dokter_id, bulan)
    // Eloquent secara default tidak mendukungnya. Kita set primaryKey ke null
    // dan non-incrementing. Relasi akan tetap berfungsi.
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;


    protected $fillable = [
        'dokter_id',
        'bulan',
        'total_gaji',
    ];

    protected function casts(): array
    {
        return [
            'total_gaji' => 'decimal:2',
        ];
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }
}