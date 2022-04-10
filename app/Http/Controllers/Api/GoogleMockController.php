<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mock\Google\ControlRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Throwable;

class GoogleMockController extends Controller
{
    /**
     * Mobil cihazlar kayıt fonksiyonu
     * @param ControlRequest $request
     * @return Response
     */
    public function control(ControlRequest $request): Response
    {
        try {
            //receiptin son değerini alır
            $substr = substr($request->receipt, -1);
        } catch (Throwable $e) {
            //herhangi bir hatada servise geri dönüş sağlar
            return new Response([
                'status' => false,
                'msg' => 'System error!',
                'err' => $e->getMessage()
            ], 400);
        }
        //son değeri tek ise
        if (intval($substr) % 2 !== 0) {
            //servise geri dönüş sağlar
            return new Response([
                'status' => true,
                'msg' => 'OK',
                'expire_date' => Carbon::now()->addHours(-6)->format('Y-m-d H:i:s')
            ]);
        } else {
            //servise geri dönüş sağlar, geçersiz değer
            return new Response([
                'status' => false,
                'msg' => 'Invalid value'
            ]);
        }
    }
}
