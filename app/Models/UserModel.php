<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// class usermodel extends Model
// {
//     use HasFactory;

//     protected $table = 'm_user'; //mendefinisikan nama tabel yang digunakan oleh model ini
//     protected $primaryKey = 'user_id'; //mendefinisikan Primary key dari table yang digunakan
// }
class UserModel extends Model
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['level_id','username','nama','password',];

    protected $fillable = ['level_id', 'username', 'nama', 'password'];
    public function level(): BelongsTo{
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id'); //relasi one to many, menggunakan foreign key level_id dan primary key level_id di table level_model
    }
}