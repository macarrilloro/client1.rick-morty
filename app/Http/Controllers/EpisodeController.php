<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EpisodeController extends Controller
{
    public function view(){
        $this->resolveAuthorization();
        return view('episodes.index');
    }
    public function index(){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->get('http://api.rick-morty.test/v1/episodes');
        return $response->json();
    }

    public function store(Request $request){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->post('http://api.rick-morty.test/v1/episodes',[
            'name' => $request->name,
            'air_date' => $request->air_date,
            'episode' => $request->episode,
            'slug' => $request->slug,
        ]);
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }
    }
    public function update($id, Request $request){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->put('http://api.rick-morty.test/v1/episodes/'.$id,[
            'name' => $request->name,
            'air_date' => $request->air_date,
            'episode' => $request->episode,
            'slug' => $request->slug,
        ]);
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }    
    }
    public function destroy($id){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->delete('http://api.rick-morty.test/v1/episodes/'.$id);
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }
    }
}
