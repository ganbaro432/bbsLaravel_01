<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "dislikes";

    public function comment(){
        return $this->belongsTo(Comment::class);
    }    
}
