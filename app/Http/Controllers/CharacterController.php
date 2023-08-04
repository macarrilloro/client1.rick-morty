<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function Laravel\Prompts\error;

class CharacterController extends Controller
{

    public function view(){
        $this->resolveAuthorization();
        return view('characters.index');
    }
    public function index(){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->get('http://api.rick-morty.test/v1/characters');
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }
    }
    public function get(){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->get('http://api.rick-morty.test/v1/characters');
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }
    }
    public function store(Request $request){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->post('http://api.rick-morty.test/v1/characters',[
            'name' => $request->name,
            'status' => intval($request->status),
            'species' => $request->species,
            'type' => $request->type,
            'gender' => $request->gender,
            'slug' => $request->slug,
            'location_id' => $request->location,
            'origin' => intval($request->origin),
            'episodes' => $request->episodes,
        ]);
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }
    }
    public function update(Request $request, $id){
        $this->resolveAuthorization();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.auth()->user()->accessToken->access_token
        ])->put('http://api.rick-morty.test/v1/characters/'.$id,[
            'name' => $request->name,
            'status' => intval($request->status),
            'species' => $request->species,
            'type' => $request->type,
            'gender' => $request->gender,
            'slug' => $request->slug,
            'location_id' => $request->location,
            'origin' => intval($request->origin),
            'episodes' => $request->episodes,
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
        ])->delete('http://api.rick-morty.test/v1/characters/'.$id);
        if ($response->status() == 422) {
            return response()->json($response->json()['errors'], 404);
        }else{
            return response()->json($response->json(), 200);
        }
    }
}
