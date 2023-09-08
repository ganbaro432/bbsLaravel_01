<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articleimage extends Model
{
    use HasFactory;
    protected $table = "articleimages";
    public $timestamps = false;

    public function article(){
        return $this->belongsTo(Article::class);
    }
}
