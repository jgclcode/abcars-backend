<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The Items that belong to the Item.
     */
    public function forms()
    {
        return $this->belongsToMany(Form::class)->withTimestamps();

        #return $this->belongsToMany(Form::class, 'items_forms', 'item_id', 'form_id', 'value');
        
    }
}
