<?php

namespace App;

use App\Models\Territoire\Departement;
use Illuminate\Database\Eloquent\Model;

class Radar extends Model
{
    //
    protected $guarded = ['id'];

    public function departements(){
        return $this->belongsToMany(Departement::class);
    }

}
