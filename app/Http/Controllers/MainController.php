<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{
    public function index()
    {
        return view('Auth');
    }

    //SERVER URL -> http://127.0.0.1:8000

    public function auth(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->post('http://127.0.0.1:8000/api/login', [
            'email'=>$request['email'],
            'password'=>$request['password']
        ]);

        if (isset($response['access_Token']))
        {
            $access_token = $response['access_Token'];
            session(['access_token' => $access_token]);

            return redirect('/List');
        }
        else
        {
            return view('Auth')->with('response',$response);
        }
        
    }

    public function list()
    {
        $access_token = session('access_token', 'default value');

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => "Bearer $access_token"
        ])->get('http://127.0.0.1:8000/api/list');
        // return $response;
        return view('List')->with('response', $response);
    }

    public function imagelist($id)
    {
        $access_token = session('access_token', 'default value');

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => "Bearer $access_token"
        ])->get("http://127.0.0.1:8000/api/list/$id");

        session(['list' => $response['images_list']]);
        session(['list_id' => $response['details']['id']]);

        return view('ImageList')->with('response', $response);
    }

    public function singleimage($id)
    {
        $list = session('list', 'default value');
        //Because list starts from 0
        $path = $list[$id-1]['path'];

        return view('SingleImage')->with('image', $path);
    }

    public function finish()
    {
        $access_token = session('access_token', 'default value');

        $id = session('list_id', 'default value');

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => "Bearer $access_token"
        ])->get("http://127.0.0.1:8000/api/close/$id");


        return redirect('/List');
    }

    public function logout()
    {
        $access_token = session('access_token', 'default value');

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => "Bearer $access_token"
        ])->get('http://127.0.0.1:8000/api/logout');
        
        session()->forget(['access_token','list','list_id']);
        return redirect('/Auth');
    }

}
