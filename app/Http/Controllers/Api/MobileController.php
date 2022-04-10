<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Mobile\AuthRequest;
use App\Models\Device;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class MobileController extends Controller
{
    /**
     * Mobil cihazlar kayıt fonksiyonu
     * @param AuthRequest $request
     * @return Response
     */
    public function register(AuthRequest $request): Response
    {
        try {
            // cihaz kayıtlıysa kayıtlı cihazı döner
            $device = Device::firstOrCreate(
                [
                    'uid' => $request->uid,
                    'appId' => $request->appId,
                    'language' => $request->language,
                    'os' => $request->os,
                ]
            );
            // cihazın önceden kayıt olup olmadığını kontrol eder
            if (!$device->wasRecentlyCreated) {
                $device->tokens()->delete();
            }
            // cihaz için yeni token oluşturur
            [$deviceId, $token] = explode('|', $device->createToken('client_token')->plainTextToken);
        } catch (Throwable $exception) {
            return new Response([
                'status' => false,
                'msg' => 'System error!',
                'err' => $exception->getMessage()
            ], 400);
        }
        return new Response([
            'client_token' => $token
        ]);
    }

    /**
     * Satın alma fonksiyonu
     * @param Request $request
     * @return Response
     */
    public function purchase(Request $request): Response
    {
        try {
            $device = Auth::user();

            if ($device->os === 'ios') {
                $mockRequest = Request::create('/api/mock/ios/control', 'POST', [
                    'receipt' => $request->receipt
                ]);
            } else {
                $mockRequest = Request::create('/api/mock/google/control', 'POST', [
                    'receipt' => $request->receipt
                ]);
            }
            $mockRequest->headers->set('Authorization', $request->headers->get('Authorization'));

            $mockResponse = json_decode(app()->handle($mockRequest)->getContent());

            if ($mockResponse->status) {
                $subscription = Subscription::whereDeviceId($device->id)->first();

                $date = Carbon::now();
                $expireDate = $date->addMonths()->format('Y-m-d H:i:s');
                $status = true;

                if ($subscription) {
                    //sona erme tarihi geçmemişse satın almayı iptal eder
                    if (strtotime($subscription->expire_date) > strtotime(Carbon::now()->format('Y-m-d H:i:s'))) {
                        $status = false;
                        $msg = 'Purchase not confirmed';
                        $expireDate = $subscription->expire_date;
                    } else {
                        $subscription->update([
                            'receipt' => $request->receipt,
                            'expire_date' => $expireDate,
                            'status' => true
                        ]);

                        // renewed event
                        $msg = 'Purchase renewed';
                    }
                } else {
                    Subscription::create([
                        'device_id' => $device->id,
                        'receipt' => $request->receipt,
                        'expire_date' => $expireDate
                    ]);

                    // started event
                    $msg = 'Purchase started';
                }

                return new Response([
                    'status' => $status,
                    'msg' => $msg,
                    'data' => [
                        'expire_date' => $expireDate
                    ]
                ]);
            } else {
                return new Response([
                    'status' => false,
                    'msg' => 'Purchase not confirmed'
                ], 406);
            }
        } catch (Throwable $exception) {
            return new Response([
                'status' => false,
                'msg' => 'System error!',
                'err' => $exception->getMessage()
            ], 400);
        }
    }

    /**
     * Ödeme kontrol fonksiyonu
     * @return Response
     */
    public function check(): Response
    {
        try {
            //güncel abonelik durumu verilerini çeker
            $subscription = Subscription::where([
                'device_id' => Auth::id()
            ])->first();
        } catch (Throwable $e) {
            //herhangi bir hatada servise geri dönüş sağlar
            return new Response([
                'status' => false,
                'msg' => 'System error!',
                'err' => $e->getMessage()
            ], 400);
        }
        //abonelik zamanı geçmiş
        if (strtotime($subscription->expire_date) < strtotime(Carbon::now()->format('Y-m-d H:i:s'))) {
            return new Response([
                'status' => false,
                'msg' => 'Subscription expired'
            ]);
        } else {
            return new Response([
                'status' => true,
                'msg' => 'OK'
            ]);
        }
    }
}
