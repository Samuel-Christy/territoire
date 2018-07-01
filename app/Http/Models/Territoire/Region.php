<?php

namespace App\Models\Territoire;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{

    protected $guarded = ['id'];

    protected $casts = ['json' => 'array'];

    //
    public function departements(){
        return $this->hasMany(Departement::class);
    }
}
