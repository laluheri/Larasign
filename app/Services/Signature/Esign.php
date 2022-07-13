<?php

namespace App\Services\Signature;

use App\Services\Signature\Client\BasicRest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Esign
{

    private $error;

    public function checkStatus($nik = '')
    {
        $response = Http::withBasicAuth(config('esign.client_id'), config('esign.client_secret'))
            ->withOptions(['verify' => false,])
            ->get(config('esign.host') . '/api/user/status/' . $nik);

        return $response;
    }
    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    public function signInvisible($nik = '', $pass = '', $pdf = '', $tag = '', $linkQR = '')
    {
        $response = Http::withBasicAuth(config('esign.client_id'), config('esign.client_secret'))
            ->withOptions(['verify' => false,])
            ->attach(
                'file',
                file_get_contents($pdf),
                'file.pdf'
            )
            ->post(
                config('esign.host') . '/api/sign/pdf',
                [
                    'nik' => $nik,
                    'passphrase' => $pass,
                    'tampilan' => 'invisible',
                    'file' => $pdf
                ]
            );
        return $response;
    }

    public function signVisible($nik = '', $pass = '', $pdf = '', $imageTTD = '', $tampilan = '', $page = '', $xAxis = '', $yAxis = '', $width = '', $height = '')
    {
        $response = Http::withBasicAuth(config('esign.client_id'), config('esign.client_secret'))
            ->withOptions(['verify' => false,])
            ->attach(
                'file',
                file_get_contents($pdf),
                'file.pdf'
            )
            ->attach(
                'imageTTD',
                file_get_contents($imageTTD),
                'imageTTD.png'
            )
            ->post(
                config('esign.host') . '/api/sign/pdf',
                [
                    'nik' => $nik,
                    'passphrase' => $pass,
                    'file' => $pdf,
                    'imageTTD' => $imageTTD,
                    'image' => 'true',
                    'tampilan' => $tampilan,
                    'page' => $page,
                    'xAxis' => $xAxis,
                    'yAxis' => $yAxis,
                    'width' => $width,
                    'height' => $height
                ]
            );
        return $response;
    }
}
