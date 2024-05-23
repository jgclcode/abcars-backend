<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell_your_car extends Model
{
    use HasFactory;

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function carmodel(){
        return $this->belongsTo(Carmodel::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    
    public function client_sale(){
        return $this->client()->with('user');
    }

    public function check_list(){
        return $this->hasOne(Check_List::class);
    }

    public function spare_parts() {
        return $this->hasMany(Spare_part::class);
    }
    
    public function painting_works() {
        return $this->hasMany(Painting_work::class);
    }
    
}
