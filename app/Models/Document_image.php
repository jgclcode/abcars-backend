<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_image extends Model
{
    use HasFactory;

    public function document(){
        return $this->belongsTo(Document::class);
    }

    public function checklist(){
        return $this->belongsTo(Check_List::class);
    }
}
