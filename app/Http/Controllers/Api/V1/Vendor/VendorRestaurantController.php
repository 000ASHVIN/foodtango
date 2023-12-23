<?php

namespace App\Http\Controllers\Api\V1\Vendor;

use App\Models\Coupon;
use App\Models\Review;
use App\Models\PaymentsToRestaurant;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\CentralLogics\RestaurantLogic;
use Illuminate\Support\Facades\Validator;
use App\Models\Order;

class VendorRestaurantController extends Controller
{
    public function get_restaurants(Request $request, $filter_data="all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $type = $request->query('type', 'all');
        $cuisine= $request->query('cuisine', 'all');
        $name= $request->query('name');
        $filter_data= $request->query('filter_data');
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::get_restaurants(zone_id:$zone_id, filter:$filter_data, limit:$request['limit'],offset: $request['offset'],type:$type, name:$name,longitude:$longitude,latitude:$latitude,cuisine:$cuisine ,veg:$request->veg ,non_veg:$request->non_veg, discount:$request->discount, top_rated:$request->top_rated  );
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting(data:$restaurants['restaurants'],multi_data: true);

        return response()->json($restaurants, 200);
    }

    public function get_latest_restaurants(Request $request, $filter_data="all")
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }

        $type = $request->query('type', 'all');
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::get_latest_restaurants(zone_id:$zone_id, limit:$request['limit'], offset:$request['offset'], type:$type ,longitude:$longitude,latitude:$latitude,veg:$request->veg ,non_veg:$request->non_veg, discount:$request->discount,top_rated: $request->top_rated);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting(data:$restaurants['restaurants'],multi_data: true);

        return response()->json($restaurants['restaurants'], 200);
    }

    public function get_popular_restaurants(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $type = $request->query('type', 'all');
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::get_popular_restaurants(zone_id:$zone_id,limit: $request['limit'], offset:$request['offset'],type: $type,longitude:$longitude,latitude:$latitude,veg:$request->veg ,non_veg:$request->non_veg, discount:$request->discount,top_rated: $request->top_rated);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting(data:$restaurants['restaurants'], multi_data:true);
        return response()->json($restaurants['restaurants'], 200);
    }


    public function recently_viewed_restaurants(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $type = $request->query('type', 'all');
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::recently_viewed_restaurants_data(zone_id:$zone_id, limit:$request['limit'], offset:$request['offset'],type: $type,longitude:$longitude,latitude:$latitude);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting(data:$restaurants['restaurants'], multi_data:true);

        return response()->json($restaurants['restaurants'], 200);
    }

    public function get_details($id)
    {
        $restaurant = RestaurantLogic::get_restaurant_details($id);
        if($restaurant)
        {
            $category_ids = DB::table('food')
            ->join('categories', 'food.category_id', '=', 'categories.id')
            ->selectRaw('IF((categories.position = "0"), categories.id, categories.parent_id) as categories')
            ->where('food.restaurant_id', $restaurant->id)
            ->where('categories.status',1)
            ->groupBy('categories')
            ->get();
            $restaurant = Helpers::restaurant_data_formatting(data: $restaurant);
            $restaurant['category_ids'] = array_map('intval', $category_ids->pluck('categories')->toArray());

            if(auth('api')->user() !== null){
                $customer_id =auth('api')->user()->id;
                Helpers::visitor_log(model:'restaurant',user_id:$customer_id,visitor_log_id:$restaurant->id,order_count:false);
            }
        }

        return response()->json($restaurant, 200);
    }

    public function get_searched_restaurants(Request $request)
    {
        if (!$request->hasHeader('zoneId')) {
            $errors = [];
            array_push($errors, ['code' => 'zoneId', 'message' => translate('messages.zone_id_required')]);
            return response()->json([
                'errors' => $errors
            ], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $type = $request->query('type', 'all');
        $longitude= $request->header('longitude');
        $latitude= $request->header('latitude');
        $zone_id= json_decode($request->header('zoneId'), true);
        $restaurants = RestaurantLogic::search_restaurants(name:$request['name'], zone_id:$zone_id, category_id:$request->category_id,limit:$request['limit'], offset:$request['offset'],type: $type,longitude:$longitude,latitude:$latitude);
        $restaurants['restaurants'] = Helpers::restaurant_data_formatting( data: $restaurants['restaurants'],multi_data: true);
        return response()->json($restaurants, 200);
    }

    public function reviews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $id = $request['restaurant_id'];


        $reviews = Review::with(['customer', 'food'])
        ->whereHas('food', function($query)use($id){
            return $query->where('restaurant_id', $id);
        })
        ->active()->latest()->get();

        $storage = [];
        foreach ($reviews as $item) {
            $item['attachment'] = json_decode($item['attachment']);
            $item['food_name'] = null;
            $item['food_image'] = null;
            $item['customer_name'] = null;
            if($item->food)
            {
                $item['food_name'] = $item?->food?->name;
                $item['food_image'] = $item?->food?->image;
                if(count($item?->food?->translations)>0)
                {
                    $translate = array_column($item->food->translations->toArray(), 'value', 'key');
                    $item['food_name'] = $translate['name'];
                }
            }
            if($item?->customer)
            {
                $item['customer_name'] = $item?->customer?->f_name.' '.$item?->customer?->l_name;
            }

            unset($item['food']);
            unset($item['customer']);
            array_push($storage, $item);
        }

        return response()->json($storage, 200);
    }

    public function get_coupons(Request $request){

        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $restaurant_id=$request->restaurant_id;
        $customer_id=$request->customer_id ?? null;

        $coupons = Coupon::Where(function ($q) use ($restaurant_id,$customer_id) {
            $q->Where('coupon_type', 'restaurant_wise')->whereJsonContains('data', [$restaurant_id])
                ->where(function ($q1) use ($customer_id) {
                    $q1->whereJsonContains('customer_id', [$customer_id])->orWhereJsonContains('customer_id', ['all']);
                });
        })->orWhereHas('restaurant',function($q) use ($restaurant_id){
            $q->where('id',$restaurant_id);
        })
        ->active()->whereDate('expire_date', '>=', date('Y-m-d'))->whereDate('start_date', '<=', date('Y-m-d'))
        ->get();
        return response()->json($coupons, 200);
    }

    public function get_payments_to_restaurant(Request $request)
    {

        // dd($request);
        $limit=$request->limit?$request->limit:25;
        $offset=$request->offset?$request->offset:1;
        $restaurant_id= $request->restaurant_id;
        $validator = Validator::make($request->all(), [
            'restaurant_id' => 'required',
        ]);
        // $validate_data = $request->validate([
        //     'restaurant_id' => 'required',
        // ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        // $type = $request->query('type', 'all');

        // $paginator = PaymentsToRestaurant::where('restaurant_id', $request->restaurant_id)->with('orders')->latest()->paginate($limit, ['*'], 'page', $offset);
        $paginator = PaymentsToRestaurant::where('restaurant_id', $request->restaurant_id)->latest()->paginate($limit, ['*'], 'page', $offset);

        $records = [];
        foreach($paginator->items() as $payment) {
            // dd(json_decode($payment->orders));
            $payment->orders = json_decode($payment->orders);
            $payment->restaurant_payment =  $payment->total_payment;
            $payment->total_commission_fees =  $payment->total_service_fees;

            unset($payment->total_payment);
            unset($payment->total_service_fees);
            
            $records[] = $payment;
        }

        $data = [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'data' => $records
            // 'data' => Helpers::product_data_formatting(data:$paginator->items(), multi_data: true, trans:true, local:app()->getLocale())
        ];

        return response()->json($data, 200);
    }

    public function get_payments_to_restaurant_details(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'restaurant_payment_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        // $type = $request->query('type', 'all');

        $record = PaymentsToRestaurant::where('id', $request->restaurant_payment_id)->with('orders')->first();

            $record->orders = json_decode($record->orders);
            $record->restaurant_payment =  $record->total_payment;
            $record->total_commission_fees =  $record->total_service_fees;

            unset($record->total_payment);
            unset($record->total_service_fees);
                    
        $data = [
            'success' => true,
            'data' => $record
        ];

        return response()->json($data, 200);
    }

    public function get_payments_to_restaurant_stats(Request $request, $restaurant_id)
    {
        
        $unpaid_orders = Order::with(['details'])->where('restaurant_id', $restaurant_id)->where('payment_status', 'paid')->where('order_status', 'delivered')->where('payment_to_restaurant', '0')->get();
        $payments_to_restaurant = PaymentsToRestaurant::where('restaurant_id', $restaurant_id)->get();
        $latest_payment_record = PaymentsToRestaurant::where('restaurant_id', $restaurant_id)->latest('created_at')->first();

        $total_due_amount = 0;
        $total_paid_amount = 0;
        $total_penalty = 0;
        $latest_payment_amount = 0;
        $latest_penalty = 0;

        if($latest_payment_record && $latest_payment_record->total_payment) {
            $latest_payment_amount = round($latest_payment_record->total_payment);
        }

        if($payments_to_restaurant && count($payments_to_restaurant)) {
            foreach ($payments_to_restaurant as $payment) {
                $total_paid_amount = $total_paid_amount + $payment->total_payment;
            }
        }

        $orders_total = 0;

        if($unpaid_orders && count($unpaid_orders)) {
            foreach ($unpaid_orders as $order) {            
                $order_sub_total = 0;
                $discount_total = 0;
                $commission = 0;
    
                foreach ($order->details as $details) {
                   $order_sub_total += $details->price * $details->quantity;
                   $discount_total += $details->discount_on_food * $details->quantity;
               }
    
               $order_sub_total = round($order_sub_total - $discount_total);
               $orders_total += $order_sub_total;
            }
        }

        $total_due_amount =   round($orders_total - round($orders_total * 0.12));

        $stats_data = [
            'total_due_amount' => $total_due_amount,
            'total_paid_amount' => $total_paid_amount,
            'total_penalty' => $total_penalty,
            'latest_payment_amount' => $latest_payment_amount,
            'latest_penalty' => $latest_penalty,
        ];

        $data = [
            'success' => true,
            'data' => $stats_data
        ];

        return response()->json($data, 200);
    }

}
