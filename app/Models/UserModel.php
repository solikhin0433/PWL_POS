<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return[];
    }
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'password',
        'nama',
        'level_id',
        'created_at',
        'updated_at',
        'avatar'
    ];

    protected $hidden = ['password']; // jangan tampilkan saat select
    protected $casts = ['password' => 'hashed']; // Mengubah tipe data password menjadi hashed
    // Relasi ke tabel level
    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
    // mendapatkan nama role
    public function getRoleName(): string
    {
        return $this->level->level_nama;
    }

    // Cek apakah user memiliki role tertentu
    public function hasRole($role): bool
    {
        return $this->level->level_kode == $role;
    }
    // Mendapatkan kode role
    // */
   public function getRole()
   {
       return $this->level->level_kode;
   }
   
}