<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentcounter extends Model
{
    use HasFactory;
    protected $table ="comment_counter";
    public $timestamps = false;

    //子テーブルの記載も必須
    public function thread(){
        $this->belongsTo(Thread::class);
    }
}
