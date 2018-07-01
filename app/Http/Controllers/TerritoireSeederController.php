<?php

namespace App\Http\Controllers;

use App\CodePostal;
use App\Commune;
use App\Departement;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class TerritoireSeederController extends Controller
{
    //

    private static $HttpClient;


    private function getRemoteURL($url, $method = 'GET')
    {
        self::$HttpClient = self::$HttpClient == null ? new \GuzzleHttp\Client() : self::$HttpClient;
        $res = self::$HttpClient->request($method, $url);

        return $res->getStatusCode() >= 200 && $res->getStatusCode() < 300 ? (\GuzzleHttp\json_decode($res->getBody(), true)) : [];
    }

    public function moreDetails(){


        $storage = Storage::disk('local');

        $communes = Commune::all(array('id','code','population'))->where('population','<=',0)->sortBy('code');
//        $communes = DB::table('communes')->whereNull('centerJSON');

        var_dump($communes->count());
        $i = 0;
        foreach ($communes as $commune) {


            $url = "https://geo.api.gouv.fr/communes?code=$commune->code&fields=codesPostaux,centre,population&format=geojson&geometry=centre";
            $filename = "/public/france/extra/$commune->code.geojson";
            //we get remote webservice only if we never did
            if (!$storage->exists($filename)) {
                $data = ($this->getRemoteURL($url));
                $storage->put($filename, json_encode($data));
//                dd($data);
            } else {
                $data = json_decode($storage->get($filename), true);
//                dd($data);
            }


            //add extra data to commune

            if(array_key_exists(0,$data['features'])){
                try{
                foreach ($data['features'][0]['properties']['codesPostaux'] as $code) {
                    $cp = CodePostal::firstOrCreate(array('code' => $code));

                    $cp->communes()->syncWithoutDetaching([$commune->id]);
                    $cp->save();

    //                dd($cp->communes());

                }

                if(array_key_exists('population',$data['features'][0]['properties']))
                    $commune->population = $data['features'][0]['properties']['population'];
                $commune->centerJSON = json_encode($data['features'][0]);
                $commune->save();

                }catch (Exception $e){
                    dd($commune,$cp,$data);
                }
            } else{
//                var_dump($commune,$data);
            }
            var_dump($commune->code.'->'.$commune->name);
        }


    }


    public function feedRegions(){
        $baseDir = 'public/france/regions';
        $store = Storage::disk('local');
        $regions = $store->allDirectories($baseDir);

        $names = [];
        foreach ($regions as $region){
            $names[] = str_replace($baseDir.'/','',$region);
            $json = ($store->get($region.'/region-'.str_replace($baseDir.'/','',$region).'.geojson'));
            $array = json_decode($json,true);
            $r = new Region(array(
                'name' => $array['properties']['nom'],
                'code' => $array['properties']['code'],
                'json' => $json,
            ));
            $r->save();
            var_dump($r->name);
            $json_departements = ($store->get($region.'/departements-'.str_replace($baseDir.'/','',$region).'.geojson'));
            $departements = (json_decode($json_departements,true));
            foreach ($departements['features'] as $departement){
                $d = new Departement(array(
                    'code' => $departement['properties']['code'],
                    'name' => $departement['properties']['nom'],
                    'json' => json_encode($departement),
                ));
               $d->region()->associate($r);
               $d->save();
                var_dump($r->name.'->'.$d->name);
               //let's search les communes !
                $dept_base_dir = 'public/france/departements';
                $datas = $store->allDirectories($dept_base_dir);
//                dd($datas);
                foreach ($datas as $dept_dir){
                    if(starts_with(str_replace($dept_base_dir.'/','',$dept_dir),$d->code)){
//                        dd($dept_dir.'/communes-'.str_replace($dept_base_dir.'/','',$dept_dir).'.geojson');
                        $commune_json = $store->get($dept_dir.'/communes-'.str_replace($dept_base_dir.'/','',$dept_dir).'.geojson');
                        $c_list = json_decode($commune_json,true);
                        foreach ($c_list['features'] as $c){
//                            dd($c);
                            if(
                                true
                                //Commune::all()->where('code','=',$c['properties']['code'])->count() == 0
                                ){
//                            $com = Commune::firstOrCreate(array(
//                                'code' => $c['properties']['code'],
//                                'name' => $c['properties']['nom'],
//                                'json' => json_encode($c)
//                            ));
                            $com = new Commune(array(
                                'code' => $c['properties']['code'],
                                'name' => $c['properties']['nom'],
                                'json' => json_encode($c)
                            ));
                            $com->departement()->associate($d);
                            var_dump($r->name.'->'.$d->name.'->'.$com->name);
                            $com->save();
                            }
                        }

                    }



                }

            }
//            dd($departements);
        }

//        var_dump($names);

    }

}
