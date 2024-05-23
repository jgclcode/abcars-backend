<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financing extends Model
{
    use HasFactory;

    public function brand() {
        return $this->belongsTo(Brand::class);
    }

    public function carmodel() {
        return $this->belongsTo(Carmodel::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function client() {
        return $this->belongsTo(Client::class)->with('user');
    }

    public function references() {
        return $this->hasMany(Reference::class);
    }
}
