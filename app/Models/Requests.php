<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    use HasFactory;

    public function carmodel() {
        return $this->belongsTo(Carmodel::class);
    }

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function client() {
        return $this->belongsTo(Client::class)->with('user');
    }

}
