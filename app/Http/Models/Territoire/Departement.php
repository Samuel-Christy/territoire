<?php

namespace App\Models\Territoire;

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
}
