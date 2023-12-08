<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountTransaction;
use App\Models\Order;
use Illuminate\Http\Request;

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
        return view('admin-views.restarestaurant.index', compact('account_transaction'));
    }

}
