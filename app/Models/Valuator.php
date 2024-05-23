<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Valuator extends Model
{
    use HasFactory;
    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function sell_your_cars(){
        return $this->belongsToMany(Sell_your_car::class);
    }
}
