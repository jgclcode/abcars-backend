<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiclebody extends Model
{
    use HasFactory;

    public function vehicles(){
        return $this.hasMany(Vehicle::class);
    }
}
