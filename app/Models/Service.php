<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function quotes(){
        return $this->belongsToMany(Quote::class);
    }

    public function incidents(){
        return $this->hasMany(Service_incident::class);
    }
}
