<?php

namespace App\Http\Controllers;

use App\Models\BannerImage;
use App\Models\Page;
use App\Models\Product;
use App\Models\WishList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index() {

        $bannerImages = BannerImage::where('status',1)->get();

        $featuredProducts = Product::where('is_featured','Yes')->orderBy('id','DESC')->with('images')->where('status',1)->take(8)->get();

        $latestProducts = Product::orderBy('id','DESC')->with('images')->where('status',1)->take(8)->get();

        return view('front.home', compact('featuredProducts','latestProducts','bannerImages'));
    }

    public function addToWishList(Request $request) {
        if (Auth::check() == false) {
            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false,
            ]);
        }

        $product = Product::find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found!</div>',
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id,
            ],
            [
                'product_id' => $request->id,
                'user_id' => Auth::user()->id,
            ]
        );

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>' . $product->title . '</strong> added to wishlist successfully!</div>',
        ]);
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        $pages = Page::orderBy('name','asc')->get();

        if ($page == null) {
            abort(404);
        }
    
        // Check for the for_registered attribute
        if ($page->for_registered == 1) {
            // Check if the user is authenticated
            if (!Auth::check()) {
                session(['url.intended' => url()->current()]);

                // User is not authenticated, redirect to login page
                return redirect()->route('account.login');
            }
        }
        
        return view('front.page', compact('page','pages'));
        
    }

    


}
