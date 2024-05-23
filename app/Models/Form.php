<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    /**
     * The Items that belong to the Form.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)->withTimestamps();

        #return $this->belongsToMany(Item::class, 'items_forms', 'form_id', 'item_id', 'value');
        
    }
}
