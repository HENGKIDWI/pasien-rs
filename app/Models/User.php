<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Nama view yang digunakan oleh model ini.
     * @var string
     */
    protected $table = 'users';

    /**
     * Menonaktifkan timestamps karena ini adalah VIEW.
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atribut yang dapat diisi secara massal.
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Atribut yang harus disembunyikan saat serialisasi.
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mendefinisikan cast tipe data untuk atribut.
     * Ini adalah bagian PALING PENTING untuk hashing password otomatis.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Mencegah Laravel mencoba menulis 'remember_token' ke VIEW.
     * Kita biarkan metode ini kosong.
     */
    public function setRememberToken($value)
    {
        // Jangan lakukan apa-apa karena VIEW tidak bisa diubah.
    }
}
