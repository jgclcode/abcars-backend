<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carmodel extends Model
{
    use HasFactory;

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function quotes(){
        return $this->hasMany(Quote::class);
    }

    public function financings() {
        return $this->hasMany(Financing::class);
    }

    public function requests() {
        return $this->hasMany(Requests::class);
    }
}
