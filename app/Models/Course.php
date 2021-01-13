<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'description'
    ];

    public function chapters(){
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

}
