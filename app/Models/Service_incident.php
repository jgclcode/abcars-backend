<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_incident extends Model
{
    use HasFactory;

    public function service(){
        return $this->belongsTo(Service::class);
    }

    public function client(){
        return $this->belongsTo(Client::class)->with('user');
    }
}
