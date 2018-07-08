<?php

namespace App\Models\Territoire;

use App\Radar;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    //
    protected $guarded = ['id','region_id'];

    protected $casts = ['json' => 'array'];

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function communes(){
        return $this->hasMany(Commune::class);
    }

    public function radars(){
        return $this->belongsToMany(Radar::class);
    }

}
