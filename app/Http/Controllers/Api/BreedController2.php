<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Breed;

class BreedController2 extends Controller
{
    public function __construct(Breed $breed){
        $this->breed = $breed;
        $this->externalUrl = "https://api.thecatapi.com/v1/breeds/";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->breed->all();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /*$user = $this->user->find($id);
        if(!$user) {
            return response()->json([
                'status' => 'não encontado'
            ]);
        }
        return response()->json($user, 201);
        */
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request){

        //$search_string= 'a';
        $method = "GET";

        $urlExterna = "https://api.thecatapi.com/v1/breeds/search?q=$request->name";

        try{
        $dbLocal = DB::table('breeds')->where('name', 'like', $request->name.'%')->get();

         if($dbLocal->isNotEmpty()){
                return $dbLocal;
            } else if($dbLocal->isEmpty()){
                $response = $this->curl($urlExterna, $method);
                $array = json_decode($response, true);

                foreach($array as $key => $value){
                    Breed::create([
                        'id' => $value['id'],
                        'name' => $value['name'],
                        'description' => $value['description'],
                        'temperament' => $value['temperament'],
                        'life_span' => $value['life_span'],
                        'alt_names' => $value['alt_names'],
                        'wikipedia_url' => $value['wikipedia_url'],
                        'origin' => $value['origin']
                    ]);
                }
                return $array;
                }
        }catch(PDOException $e){
            abort(500,"Mensagem.....");
        }catch(QueryException $e){
            abort(403,"Mensagem.....");
        }
        /*
        //$urlCat = "https://api.thecatapi.com/v1/breeds?limit=4&page=0";
        $urlLocal = "http://laravel-catapi.test/api/breed/search/$search_string";
        $urlExterna = "https://api.thecatapi.com/v1/breeds/search?q=$search_string";

        //$response = $this->curl($urlLocal, $method);
        //$array = json_decode($response, true);
        //dd($array);

        if($bre->isEmpty()) {
            //$urlExterna;

            $response = $this->curl($urlExterna, $method);

            $array = json_decode($response, true);
        } else {

           //$response = $this->curl($urlLocal, $method);
            return $bre;;
//           $array = json_decode($response, true);
        }
        //$response = $this->curl($urlExterna, $method);
        //$array = json_decode($response, true);
        /*
        for ($x=0; $x < count($array); $x++) {
            $url = "https://api.thecatapi.com/v1/images/search?breed_id=" . $array[$x]["id"];
            $image = $this->curl($url, "GET");
            $image = json_decode($image, true);
            $array[$x]["urlImage"] = $image[0]["url"];
        }
        */
        //return view('breeds', compact('array'));
    }

    private function curl($url, $method){

        if(!$method)
            $method = "GET";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            //CURLOPT_URL => "https://api.thecatapi.com/v1/breeds/search?q=sib",
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                "x-api-key: DEMO-API-KEY"
            ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
