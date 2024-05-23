<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;

    public function damage_images() {
        return $this->hasMany(Damage_image::class);
    }
}
