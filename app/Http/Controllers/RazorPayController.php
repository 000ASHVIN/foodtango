<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;

class RazorPayController extends Controller
{
    public function handleWebhook(Request $request)
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
            // Handle the webhook event
            $event = json_decode($request->getContent(), true);
            // Process the event as needed

            error_log($event);
            dd($event);

            return response()->json(['success' => true], 200);
        } catch (\Throwable $e) {
            // Handle signature verification failure
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


    public function payWithRazorpay()
    {
        return view('razor-pay');
    }

    public function payment(Request $request, $order_id)
    {
        $order = Order::where(['id' => $order_id])->first();
        //get API Configuration
        $api = new Api(config('razor.razor_key'), config('razor.razor_secret'));
        //Fetch payment information by razorpay_payment_id
        $payment = $api->payment->fetch($request['razorpay_payment_id']);

        if (count($request->all()) && !empty($request['razorpay_payment_id'])) {
            try {
                // $response = $api->payment->fetch($request['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $order = Order::where(['id' => $payment->description])->first();
                $tr_ref = $request['razorpay_payment_id'];

                $order->transaction_reference = $tr_ref;
                $order->payment_method = 'razor_pay';
                $order->payment_status = 'paid';
                $order->order_status = 'confirmed';
                $order->confirmed = now();
                $order->save();
                Helpers::send_order_notification($order);
            } catch (\Exception $e) {
                info($e);
                Order::
                where('id', $order)
                ->update([
                    'payment_method' => 'razor_pay',
                    'order_status' => 'failed',
                    'failed'=>now(),
                    'updated_at' => now(),
                ]);
                if ($order->callback != null) {
                    return redirect($order->callback . '&status=fail');
                }else{
                    return \redirect()->route('payment-fail');
                }
            }
        }

        if ($order->callback != null) {
            return redirect($order->callback . '&status=success');
        }else{
            return \redirect()->route('payment-success');
        }
    }

}
