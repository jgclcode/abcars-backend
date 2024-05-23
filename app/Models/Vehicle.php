<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    
    public function vehiclebody(){
        return $this->belongsTo(Vehiclebody::class);
    }

    public function carmodel(){
        return $this->belongsTo(Carmodel::class)->with('brand');
    }
    
    public function branch(){
        return $this->belongsTo(Branch::class)->with('state');
    }

    public function client(){
        return $this->belongsTo(Client::class)->with('user');
    }

    public function vehicle_images(){
        return $this->hasMany(Vehicle_image::class);
    }

    public function vehicle_360_images(){
        return $this->hasMany(Vehicle_360_image::class);
    }

    public function images(){
        return $this->belongsTo(Vehicle_image::class);
   }
    
    public function choices(){
        return $this->hasMany(Choice::class);
    }

    public function aggregates(){
        return $this->hasMany(Aggregate::class);
    }

    public function shields(){
        return $this->belongsToMany(Shield::class);
    }

    public function checks(){
        return $this->hasMany(Check_vehicle::class);
    }

    public function setsImage(){
        return $this->hasMany(SetImage::class);
    }
}
