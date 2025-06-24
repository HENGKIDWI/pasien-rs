<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users'; // view 'users'
    public $timestamps = false;

    protected $fillable = ['email', 'password', 'role', 'name'];
    protected $hidden = ['password'];

    // optional
    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isPengunjung()
    {
        return $this->role === 'pengunjung';
    }
}
