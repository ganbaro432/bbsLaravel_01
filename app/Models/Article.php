<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $table = "articles";
    protected $fillable = ['body'];

    //時間不要
    const UPDATED_AT = null;

    public function Articleimage(){
        return $this->hasMany(Articleimage::class);
    }
}
