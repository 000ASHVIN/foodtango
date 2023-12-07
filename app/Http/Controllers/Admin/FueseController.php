<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use App\Models\Order;
use App\Models\Wishlist;
use App\Scopes\ZoneScope;
use App\Models\Restaurant;
use App\Models\DeliveryMan;
use Illuminate\Http\Request;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SubscriptionTransaction;

class FueseController extends Controller
{
    public function fuse(Request $request)
    {
		 $data['status']='success';
		   echo json_encode($data);
        
    }

    
}
