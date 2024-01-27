<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Razorpay\Api\Api;

class VerifyWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $webhookSecret = "XvWHOSzwLVfgStBoQEw0I6rf";
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));

        $attributes = array(
            'razorpay_signature' => $request->header('X-Razorpay-Signature'),
            'razorpay_event_id' => $request->header('X-Razorpay-Event-Id'),
            'razorpay_webhook_secret' => $webhookSecret,
            'webhook_body' => $request->getContent(),
        );

        try {
            $api->utility->verifyWebhookSignature($attributes);
            error_log('Request from middleware verified');

            return $next($request);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
