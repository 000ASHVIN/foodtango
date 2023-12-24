<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\Admin;
use App\Models\Order;
use App\Models\PaymentsToRestaurant;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\Helpers;

class PaymentsRestarestaurantController extends Controller
{
    public function index(Request $request)
    {

        $account_transaction = AccountTransaction::latest()->paginate(config('default_pagination'));
        // if (session()->has('zone_filter') == false) {
        //     session()->put('zone_filter', 0);
        // }

        // if (session()->has('order_filter')) {
        //     $request = json_decode(session('order_filter'));
        // }

        // Order::where(['checked' => 0])->update(['checked' => 1]);

        // $orders = Order::with(['customer', 'restaurant'])
        //     ->when(isset($request->zone), function ($query) use ($request) {
        //         return $query->whereHas('restaurant', function ($q) use ($request) {
        //             return $q->whereIn('zone_id', $request->zone);
        //         });
        //     })
        //     ->when($status == 'scheduled', function ($query) {
        //         return $query->whereRaw('created_at <> schedule_at');
        //     })
        //     ->when($status == 'searching_for_deliverymen', function ($query) {
        //         return $query->SearchingForDeliveryman();
        //     })
        //     ->when($status == 'pending', function ($query) {
        //         return $query->Pending();
        //     })
        //     ->when($status == 'accepted', function ($query) {
        //         return $query->AccepteByDeliveryman();
        //     })
        //     ->when($status == 'processing', function ($query) {
        //         return $query->Preparing();
        //     })
        //     ->when($status == 'food_on_the_way', function ($query) {
        //         return $query->FoodOnTheWay();
        //     })
        //     ->when($status == 'delivered', function ($query) {
        //         return $query->Delivered();
        //     })
        //     ->when($status == 'canceled', function ($query) {
        //         return $query->Canceled();
        //     })
        //     ->when($status == 'failed', function ($query) {
        //         return $query->failed();
        //     })
        //     ->when($status == 'requested', function ($query) {
        //         return $query->Refund_requested();
        //     })
        //     ->when($status == 'rejected', function ($query) {
        //         return $query->Refund_request_canceled();
        //     })
        //     ->when($status == 'refunded', function ($query) {
        //         return $query->Refunded();
        //     })
        //     ->when($status == 'scheduled', function ($query) {
        //         return $query->Scheduled();
        //     })
        //     ->when($status == 'on_going', function ($query) {
        //         return $query->Ongoing();
        //     })
        //     ->when(($status != 'all' && $status != 'scheduled' && $status != 'canceled' && $status != 'refund_requested' && $status != 'refunded' && $status != 'delivered' && $status != 'failed'), function ($query) {
        //         return $query->OrderScheduledIn(30);
        //     })
        //     ->when(isset($request->vendor), function ($query) use ($request) {
        //         return $query->whereHas('restaurant', function ($query) use ($request) {
        //             return $query->whereIn('id', $request->vendor);
        //         });
        //     })
        //     ->when(isset($request->orderStatus) && $status == 'all', function ($query) use ($request) {
        //         return $query->whereIn('order_status', $request->orderStatus);
        //     })
        //     ->when(isset($request->scheduled) && $status == 'all', function ($query) {
        //         return $query->scheduled();
        //     })
        //     ->when(isset($request->order_type), function ($query) use ($request) {
        //         return $query->where('order_type', $request->order_type);
        //     })
        //     ->when($request?->from_date != null && $request?->to_date != null, function ($query) use ($request) {
        //         return $query->whereBetween('created_at', [$request->from_date . " 00:00:00", $request->to_date . " 23:59:59"]);
        //     })
        //     ->Notpos()
        //     ->hasSubscriptionToday()
        //     ->orderBy('schedule_at', 'desc')
        //     ->paginate(config('default_pagination'));

        // $orderstatus = $request?->orderStatus ?? [];
        // $scheduled =  $request?->scheduled ?? 0;
        // $vendor_ids =  $request?->vendor ?? [];
        // $zone_ids = $request?->zone ?? [];
        // $from_date =  $request?->from_date ?? null;
        // $to_date = $request?->to_date ?? null;
        // $order_type =  $request?->order_type ?? null;
        // $total = $orders->total();

        // dd( $account_transaction);
        $admins = Admin::where('role_id', 1)->get();
        $input = $request->all();
        $payment_to_restaurant = Order::where('payment_to_restaurant', 0)->get();
        $orders = collect();
        $restaurant = 0;
        if(!empty($input)) {
            $orders = Order::with(['customer', 'restaurant', 'details'])->where('payment_to_restaurant', 0);
            // dd($orders->get());
            if($request->has('restaurant_id') && !empty($input['restaurant_id'])) {
                $orders = $orders->where('restaurant_id', $input['restaurant_id']);
                $restaurant = Restaurant::find($input['restaurant_id']);
            }
            if($request->has('start_date') && !empty($input['start_date']) && $request->has('end_date') && !empty($input['end_date'])) {
                $startDate = Carbon::parse($input['start_date']);
                $endDate = Carbon::parse($input['end_date'])->endOfDay();
                $orders = $orders->whereBetween('created_at', [$startDate, $endDate]);
            }
            // if($request->has('method') && !empty($input['method'])) {
            //     $orders = $orders->where('payment_method', 'LIKE', '%'.$input['method'].'%');
            // }

            // if($request->has('ref') && !empty($input['ref'])) {
            //     $orders = $orders->where('transaction_reference', 'LIKE', '%'.$input['ref'].'%');
            // }
            
            // if($request->has('payment_by') && !empty($input['payment_by'])) {
            //     // $orders = $orders->where('payment_by', 'LIKE', '%'.$input['payment_by'].'%');
            // }
            $orders = $orders->where('order_status', 'delivered')->where('payment_status', 'paid')->where('payment_to_restaurant', false)->get();
            // dd($orders);
        }
        return view('admin-views.restarestaurant.index', compact('account_transaction', 'admins', 'orders', 'restaurant','payment_to_restaurant'));
    }

    public function confirmPayment(Request $request) {
        $this->validate($request, [
            'order_ids' => 'required'
        ], ['order_ids' => 'Please select orders.']);

        $data['orders'] = json_encode($request->order_ids);
        $data = array_merge($data, $request->form_data);


        if(!$data['method']) {
            $error_data = [
                'success' => false,
                'errors' => '',
                'message' => 'Method is required'
            ];

            return response()->json($error_data, 422);
        }

        if(!$data['payment_by']) {
            $error_data = [
                'success' => false,
                'errors' => '',
                'message' => 'Please select payment by'
            ];

            return response()->json($error_data, 422);
        }

        $orders = Order::with(['details'])->whereIn('id', $request->order_ids)->get();

        $orderTotal = $orderFee = 0;
        $order_details = [];
        foreach ($orders as $order) {
             $order->payment_to_restaurant = 1;
             
             $order_sub_total = 0;
             $discount_total = 0;
             $commission = 0;
             $order_commission = $order->admin_commission;

             foreach ($order->details as $details) {
                $order_sub_total += $details->price * $details->quantity;
                $discount_total += $details->discount_on_food * $details->quantity;
             }

            $order_sub_total = round($order_sub_total - $discount_total);

            $commission = round(($order_sub_total * $order_commission) / 100);
            

            $order_data = clone $order;

            $order_data->order_total_amount = $order_sub_total;
            $order_data->commission_amount = $commission;
            $order_data->payment_to_restaurant_amount = round($order_sub_total - $commission);;

            unset($order_data->details);
            unset($order_data->delivery_address);

            $order_details[] = $order_data;

            $orderTotal += $order_sub_total;
            $orderFee += $commission;
            
            $order->save();
        }

        // return response()->json($order_details, 200);

        // $encode =  json_encode($order_details);
        // dd($order_details);

        $data['total_order_payment'] = $orderTotal;
        $data['total_service_fees'] = $orderFee;
        $data['order_details'] = json_encode($order_details);
        $data['total_payment'] = $orderTotal - $orderFee;
        $data['reference'] = $data['ref'];
        unset($data['ref']);

        $record = PaymentsToRestaurant::create($data);

        foreach ($orders as $order) {
           $order->restaurant_payment_id = $record->id;       
           $order->save();
        }

        return response()->json(['status' => 'success']);
    }
}
