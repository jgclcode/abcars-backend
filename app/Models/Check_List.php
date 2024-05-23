<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check_List extends Model
{
    //use HasFactory;

    public $table = "check_lists";

    public function sell_your_cars(){
        return $this->belongsTo(Sell_your_car::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function user_technician(){
        return $this->technician()->with('user');
    }

    public function technician(){
        return $this->belongsTo(Technician::class);
    }

    public function document_images() {
        return $this->hasMany(Document_image::class);
    }

    public function damage_images() {
        return $this->hasMany(Damage_image::class);
    }
}
