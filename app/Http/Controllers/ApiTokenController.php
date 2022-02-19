<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DateTime;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;

class ApiTokenController extends Controller
{
    public function update(Request $request)
    {
        $token = Str::random(80);

        $request->user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();

        return redirect('/');
    }

    public function zapros(Request $request )
    {
        if($user = Auth::user()) {

            $date = new DateTime($request->date);
            $date = $date->format('d-m-Y');

            if (Auth::user()->api_token == $request->api_token) {

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . $date,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_TIMEOUT => 30000,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                    ),
                ));
                $otvet = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    $xml = simplexml_load_string($otvet);
                    $code = json_encode($xml);
                    $decode = json_decode($code,TRUE);
                    $contains = Arr::has($decode, 'Valute.0.NumCode');

                    if ( $contains ) {
                        return redirect('zapros/' . $date)->with( ['data' => $decode] );
                    } else {
                        header('HTTP/1.0 404 Not Found');

                    }
                }
            }
        } else {
            header('HTTP/1.0 401 Unauthorized');

        }



    }
    public function otvet()
    {
        $data = Session::get('data');

        if ($data == null) {
            header('HTTP/1.0 401 Unauthorized');
            exit;
        } else {
            $data = $data['Valute'];
            return view('zapros')->with('data', $data);
        }


    }


}
