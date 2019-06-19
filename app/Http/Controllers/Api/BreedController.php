<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Breed;

class BreedController extends Controller
{
    /**
     * Recebe uma model do tipo Breed
     * Receives breed type model
     * @var Breed
     */
    private $breed;
    /**
     * recebe a url externa da api
     * @var string
     */
    private $externalUrl;

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

    /**
     * @return mixed
     * @param  \Illuminate\Http\Request  $request
     */
    public function search(Request $request){

        $urlExterna = $this->externalUrl."search?q=$request->name";
        $internalSearch = $this->breed->where('name', 'like', '%'.$request->name.'%')->get();

        if($internalSearch->isEmpty()) {
            $response = $this->curl($urlExterna);
            $array = json_decode($response, true);
        } else {
           return $internalSearch;
        }
    }

    /**
     * grava os dados da api no banco
     * @return void
     */
    public function apiStore()
    {
        /*Se o metodo for get não precisa passar o segundo parametro de forma explicita*/
        $response = $this->curl($this->externalUrl);
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
            abort(403,"Mensagem.....");
        }
        /*for ($x=0; $x < count($array); $x++) {
            $url = "https://api.thecatapi.com/v1/images/search?breed_id=" . $array[$x]["id"];
            $image = $this->curl($url, "GET");
            $image = json_decode($image, true);
            $array[$x]["urlImage"] = $image[0]["url"];
        }*/

    }


    /**
     * faz requisição para uma api qualquer
     * @param $url
     * @param null $method
     * @return bool|string
     */
    private function curl($url, $method = null){
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
