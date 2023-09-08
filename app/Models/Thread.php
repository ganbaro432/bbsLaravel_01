<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    protected $table = "threads";

    //時間不要
    const UPDATED_AT = null;

    //リレーション
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function commentcounter(){
        return $this->hasOne(Commentcounter::class);
    }


}
