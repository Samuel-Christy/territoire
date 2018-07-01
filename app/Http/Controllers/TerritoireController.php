<?php

namespace App\Http\Controllers;

use App\CodePostal;
use App\Commune;
use App\Departement;
use App\Region;
use Illuminate\Http\Request;

class TerritoireController extends Controller
{
    //

    public function region($code=null){
        if(is_null($code)){
            $r = Region::all();
        } else {
            $r = Region::all()->where('code','=',$code);
        }

        return $r;
    }

    public function departement($code=null){
        if(is_null($code)){
            $r = Departement::all()->sortBy('code');
        } else {
            $r = Departement::all()->where('code','=',$code);
        }

        return $r;
    }


    public function commune($code=null){
        if(is_null($code)){
            $r = Commune::all()->sortBy('code');
        } else {
            $r = Commune::all()->where('code','=',$code);
        }

        return $r;
    }


    public function codePostal($code=null){
        if(is_null($code)){
            $r = CodePostal::all()->sortBy('code');
        } else {
            $r = CodePostal::with('communes')->where('code','=',$code)->get();
        }
        return $r;
    }

}
