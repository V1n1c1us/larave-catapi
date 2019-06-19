<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Breed;

class BreedController extends Controller
{
    public function __construct(){

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $search_string= 'sib';

        //$urlCat = "https://api.thecatapi.com/v1/breeds?limit=4&page=0";

        $url = "https://api.thecatapi.com/v1/breeds/search?q=$search_string";

        $method = "GET";

        $response = $this->curl($url, $method);

        $array = json_decode($response, true);

        try {
            foreach($array as $key => $value){
                Breed::create([
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'temperament' => $value['temperament'],
                    'life_span' => $value['life_span'],
                    'alt_names' => $value['alt_names'],
                    'wikipedia_url' => $value['wikipedia_url'],
                    'origin' => $value['origin']
                ]);
            }
        }catch(QueryException $e){
            //flash()->error('Mensagem para o usuário');
            abort(403,"Mensagem.....");
        }
        for ($x=0; $x < count($array); $x++) {
            $url = "https://api.thecatapi.com/v1/images/search?breed_id=" . $array[$x]["id"];
            $image = $this->curl($url, "GET");
            $image = json_decode($image, true);
            $array[$x]["urlImage"] = $image[0]["url"];
        }

        return view('breeds', compact('array'));
    }

    /*
    *Consome api's
    *@param $url = Api URL url da api
    *@param $method = Call method. If the calling method has not been set, the get method will be assigned
    *@param $method = chamada do método, se a chamada do meétodo não for definido, será atribuido o método get
    */
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
        /*
        *A função curl foi transferida para uma função para que pudesse ser reaproveitada em outras partes do código;
        */
        //$search_string= 'sib';
        $url = "https://api.thecatapi.com/v1/breeds?limit=4&page=0";
        //$url = "https://api.thecatapi.com/v1/breeds/search?q=$search_string";
        $method = "GET";

        $response = $this->curl($url, $method);


        $array = json_decode($response, true);


        try{
            foreach($array as $key => $value){

                $sql = "INSERT INTO breeds (name) VALUES ('
                        ".$value['name'].",".$value['temperament'].",
                        ".$value['life_span'].",".$value['wikipedia_url'].",
                        ".$value['origin']."')";

                Breed::create([
                    'name' => $value['name'],
                    'temperament' => $value['temperament'],
                    'life_span' => $value['life_span'],
                    'alt_names' => $value['alt_names'],
                    'wikipedia_url' => $value['wikipedia_url'],
                    'origin' => $value['origin']
                ]);
            }
        }catch (QueryException $e){
            echo $e;
        }


//        dd($array);
        //fará um loop para cada elemento retornado, realizando uma nova chamada
        //Ao obter a resposta, criará uma nova chave no objeto array chamada urlImage e armazenará a url da imagem
        for ($x=0; $x < count($array); $x++) {
            $url = "https://api.thecatapi.com/v1/images/search?breed_id=" . $array[$x]["id"];
            $image = $this->curl($url, "GET");
            $image = json_decode($image, true);
            $array[$x]["urlImage"] = $image[0]["url"];
        }

        return view('breeds', compact('array'));
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
}
