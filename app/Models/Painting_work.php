<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Painting_work extends Model
{
    use HasFactory;
    public function sell_your_cars() {
        return $this->belongsTo(Sell_your_car::class);
    }
}
