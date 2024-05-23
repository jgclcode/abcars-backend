<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }
}
