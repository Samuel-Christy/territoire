<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodePostal extends Model
{
    //

    protected $guarded = ['id'];

    public function communes(){
        return $this->belongsToMany(Commune::class);
    }
}
