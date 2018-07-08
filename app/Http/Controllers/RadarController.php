<?php

namespace App\Http\Controllers;

use App\Models\Territoire\Departement;
use App\Radar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RadarController extends Controller
{
    //

    private static $HttpClient;

    private function getRemoteURL($url, $method = 'GET')
    {
        self::$HttpClient = self::$HttpClient == null ? new \GuzzleHttp\Client() : self::$HttpClient;


        $res = self::$HttpClient->request($method, $url);

        return $res->getStatusCode() >= 200 && $res->getStatusCode() < 300 ? (\GuzzleHttp\json_decode($res->getBody(), true)) : [];
    }

    public function seed(){
        $storage = Storage::disk('local');
        $baseDir = 'public/france';

        $liste = json_decode(
            $storage->get($baseDir.'/radars/radars.json'),true);

        foreach ($liste as $r){
            $itm = Radar::updateOrCreate(array(
                'code' => $r['id'],
                'type'=> $r['type'],
                'labelType'=> $r['typeLabel'],
                'lat'=> $r['lat'],
                'lng'=> $r['lng'],
            ));
           if (!$storage->exists($baseDir.'/radars/itms/'.$itm->id.'.json')){
               var_dump('downloading infos : '.$itm->id);
               $storage->put($baseDir.'/radars/itms/'.$itm->id.'.json',json_encode($this->getRemoteURL('https://radars.securite-routiere.gouv.fr/radars/'.$r['id'].'?_format=json'))) ;
           }


//            $this->radarInfo($itm,$infos);
        }

    }


    public function additionnal_seed($id=0){
        $radars = $id == 0
            ? Radar::all()
            : Radar::where('id','>=',$id)->get();



        foreach ($radars as $itm){
        $infos = json_decode(Storage::disk('local')->get('public/france/radars/itms/'.$itm->id.'.json'),true);
        dd($this->radarInfo($itm,$infos));
//        dd($itm,$infos);

//        $itm->json = json_encode($infos);
//        $itm->radarInstallDate = Carbon::parse($infos['radarInstallDate']);
//        $itm->radarPlace =  is_array($infos['radarPlace']) ? null : $infos['radarPlace'];
//        $itm->radarDirection = is_array($infos['radarDirection']) ? null : $infos['radarDirection'];
//        $itm->radarEquipment = $infos['radarEquipment'];
//        $itm->save();
        var_dump($itm->id.' complete');
            //this is OK !
//            foreach ($this->radarDepartements($itm,$infos) as $dept_nbr){
//
//                $dept_nbr_q = $dept_nbr > 9 ? "$dept_nbr" : "0$dept_nbr";
//
//                $d = Departement::where('code','=',$dept_nbr_q)->first();
//                $itm->departements()->syncWithoutDetaching([$d->id]);
//                var_dump('radar/dept : '.$itm->id.' => '.$d->id.' ('.$dept_nbr_q."=>".$d->code.')');
//
//            }



        }


    }

    public function radarInfo($itm,$infos){
        dd($infos['rulesMesured']);
        if(count($infos['rulesMesured'])>1)
            dd($infos['rulesMesured']);
    }


    public function radarDepartements(Radar $radar, Array $infos){

        $departements = explode(' ',$infos['department']);
        $dpts = [];
        foreach ($departements as $d){
            $dpts[] = explode('&',$d);
        }
        $dpts = array_flatten($dpts);
        $r = [];
        foreach ($dpts as $d){
            if(is_numeric($d))
                $r[]=0+$d;
        }
        return $r;
    }

}
