<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public function branches(){
        return $this->hasMany(Branch::class);
    }

    public function financings() {
        return $this->hasMany(Financing::class);
    }
}
