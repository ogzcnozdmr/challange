<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Event\EventRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class CallbackController extends Controller
{
    /**
     * Application event fonksiyonu
     * @param EventRequest $request
     * @return Response
     */
    public function event(EventRequest $request): Response
    {
        try {
            if (rand(10000, 100000) % 2 !== 0) throw new \Exception();
            // Event başarılı olursa log tutuyoruz
            Log::info('UID: ' . $request->uid . ' APPID: ' . $request->appId . ' EVENT: ' . $request->event . ' callback işlemi başarılı!');
        } catch (Throwable $e) {
            // Event başarısız olursa log tutuyoruz
            Log::info('UID: ' . $request->uid . ' APPID: ' . $request->appId . ' EVENT: ' . $request->event . ' callback işlemi başarısız!');
            //herhangi bir hatada servise geri dönüş sağlar
            return new Response(['status' => false, 'msg' => 'System error!', 'err' => $e->getMessage()], 500);
        }
        return new Response(['status' => true, 'msg' => 'OK']);
    }
}
