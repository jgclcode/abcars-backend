<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    public function client(){
        return $this->belongsTo(Client::class)->with('user');
    }
    
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    
    public function carmodel(){
        return $this->belongsTo(Carmodel::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class);
    }
}
