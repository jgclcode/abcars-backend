<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage_image extends Model
{
    use HasFactory;

    public function damage(){
        return $this->belongsTo(Damage::class);
    }

    public function checklist(){
        return $this->belongsTo(Check_List::class);
    }
}
