<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
    
    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function clientWithUser() {
        return $this->client()->with('user');
    }
}
