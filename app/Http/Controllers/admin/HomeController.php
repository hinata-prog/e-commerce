<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index()
    {
        $ordersCount = Order::where('status', '!=', 'cancelled')->count();
        $productsCount = Product::count();
        $usersCount = User::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        //current month revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        $totalCurrentMonthRevenue = Order::where('status', '!=', 'cancelled')
        ->whereDate('created_at', '>=', $startOfMonth)
        ->whereDate('created_at','<=', $currentDate)
        ->sum('grand_total');

        //last month revenue
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $totalLastMonthRevenue = Order::where('status', '!=', 'cancelled')
        ->whereDate('created_at', '>=', $lastMonthStart)
        ->whereDate('created_at','<=', $lastMonthEnd)
        ->sum('grand_total');
        $lastMonthName = Carbon::now()->subMonth()->format('M');

        //last 30 days sale
        $lastThirtyDaysStart = Carbon::now()->subDays(30)->format('Y-m-d');
        $totalLastThirtyDaysRevenue = Order::where('status', '!=', 'cancelled')
        ->whereDate('created_at', '>=', $lastThirtyDaysStart)
        ->whereDate('created_at','<=', $currentDate)
        ->sum('grand_total');

        //delete temp images here
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');
        $currentDateTime = Carbon::now();
        $tempImages = TempImage::where('created_at','<=', $currentDateTime)->get();
        foreach( $tempImages as $image ){
            $path = public_path('/temp/'. $image->name);
            $thumbPath = public_path('/temp/thumb/'. $image->name);

            //Delete main image
            if( File::exists( $path ) ){
                File::delete( $path );
            }

            if( File::exists( $thumbPath ) ){
                File::delete( $thumbPath );
            }

            TempImage::where('id',$image->id)->delete();
        }


       // Retrieve orders that meet the conditions
        $ordersToDelete = Order::where('created_at', '<=', $currentDateTime)
        ->where('payment_status', 'unpaid')
        ->get();

        // Delete each order and its associated order items
        foreach ($ordersToDelete as $order) {
        // Delete associated order items
        $order->orderItems()->delete();

        // Delete the order itself
        $order->delete();
        }

        return view("admin.dashboard", compact("ordersCount", "productsCount", "usersCount", "totalRevenue","totalCurrentMonthRevenue","totalLastMonthRevenue","lastMonthName","totalLastThirtyDaysRevenue"));
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
