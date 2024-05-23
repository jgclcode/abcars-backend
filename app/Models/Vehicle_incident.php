<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle_incident extends Model
{
    use HasFactory;

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function client(){
        return $this->belongsTo(Client::class)->with('user');
    }
}
