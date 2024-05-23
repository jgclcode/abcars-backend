<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }
    
    public function carmodels(){
        return $this->hasMany(Carmodel::class);
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
