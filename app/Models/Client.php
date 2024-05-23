<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }
    
    public function source(){
        return $this->belongsTo(Source::class);
    }

    public function vehicles(){
        return $this->hasMany(Vehicle::class);
    }

    public function choices(){
        return $this->hasMany(Choice::class);
    }

    public function choices_with_vehicle(){
        return $this->hasMany(Choice::class)->with('vehicle');
    }
    
    public function quotes(){
        return $this->hasMany(Quote::class);
    }

    public function sell_your_cars(){
        return $this->hasMany(Sell_your_car::class);
    }

    public function service_incidents(){
        return $this->hasMany(Service_incident::class);
    }

    public function vehicle_incidents(){
        return $this->hasMany(Vehicle_incident::class);
    }

    public function financings() {
        return $this->hasMany(Financing::class);
    }

    public function requests() {
        return $this->hasMany(Requests::class);
    }

    public function reward_requests() {
        return $this->hasMany(RewardRequest::class);
    }

    public function policies(){
        return $this->hasMany(Policie::class);
    }
}
