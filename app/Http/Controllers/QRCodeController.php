<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use App\Services\Signature\Esign;
use Twilio\Rest\Client;

use QrCode;

class QRCodeController extends Controller
{

    public function index(){
        $receiverNumber = "+6287759671723";
        $message = "This is testing from ItSolutionStuff.com";
  
        try {
  
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
            $twilio_number = getenv("TWILIO_FROM");
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
  
            dd('SMS Sent Successfully.');
  
        } catch (Exception $e) {
            dd("Error: ". $e->getMessage());
        }
    }
    public function simpleQr()
    {
       return QrCode::size(300)->generate('A basic example of QR code!');
    }  
    public function colorQr()
    {
              return QrCode::size(300)
                     ->backgroundColor(255,55,0)
                     ->generate('Color QR code example');
    }    
    public function imageQr()
    {
        $image = QrCode::format('png')
                 ->merge('images/klu.jpg', 0.4, true)
                 ->size(500)->errorCorrection('H')
                 ->generate('https://google.com');
        file_put_contents(public_path('qrcode/a.png'), $image);
        return response($image)->header('Content-type','image/png');
    }
    
    public function signDoc(){
        $esign = new Esign();
        $nik = '5208011708900006';
        $pass = 'Lombok2022';
        $tampilan = 'VISIBLE'; 
        $page = '1'; 
        $xAxis = '10'; 
        $yAxis = '10'; 
        $width = '100'; 
        $height = '100';
        $pdf = public_path('documents/pdf.pdf');
        $imageTTD = public_path('qrcode/a.png');

        $response = $esign->signVisible($nik , $pass , $pdf , $imageTTD , $tampilan , $page , $xAxis , $yAxis , $width , $height);
        if($response->status() != 200 ){
                $response = json_decode($response);
                return $response->error;
            }
            return Storage::put('public/signed/file.pdf', $response);
    }

    public function message(){
        
        $sid    = env('TWILIO_SID') ;
        $token  = env('TWILIO_AUTH_TOKEN');
        $from   = env('TWILIO_FROM');
        $twilio = new Client($sid, $token);
        $title  = 'SPT MONEV TTE';
        $url    = 'https://ttd.lombokutarakb.go.id'; 
         
        $message = $twilio->messages 
        ->create("whatsapp:+6287759671723", // to 
                array( 
                    "from" => 'whatsapp:+14155238886',       
                    "body" => 'Satu Document  *'.$title.'* melalui '.$url, 
                ) 
        ); 

        return 'success';
    }
}
