<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    //

    protected $guarded=['id','departement_id'];

    protected $casts = [
        'centerJSON' => 'array',
        'json' => 'array',

    ];

    public function departement(){
        return $this->belongsTo(Departement::class);
    }

    public function codePostals(){
        return $this->belongsToMany(CodePostal::class);
    }




}
