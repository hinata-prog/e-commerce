<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null){

        $categorySelected = '';
        $subCategorySelected = '';
        $categories = Category::orderBy('name','ASC')->with('subCategories')->where('status', 1)->get();
        $products = Product::where('status',1)->where('measurement_value','>',0.00);

        //apply filters here
        if(!empty($categorySlug)){
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }
        
        if(!empty($categorySlug) && empty($subCategorySlug)){
            $products = $products->where('category_id',$category->id);
            $subCategorySelected = 'all';
        }

        if(!empty($subCategorySlug)){
            if($subCategorySlug == 'other'){
                $products = $products->where('sub_category_id',null);
                $subCategorySelected = 'other';
                
            }else{
                $category = Category::where('slug', $categorySlug)->first();
                $subCategory = SubCategory::where('slug', $subCategorySlug)->where('category_id',$category->id)->first();
    
                $products = $products->where('sub_category_id',$subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
            

        }


        if($request->get('price_max') != '' && $request->get('price_min') != ''){
            if($request->get('price_max') == 2000){
                $products = $products->whereBetween('price',[intval($request->get('price_min')),10000000]);

            }else{
                $products = $products->whereBetween('price',[intval($request->get('price_min')), intval($request->get('price_max'))]);

            }
        }

        if($request->get('sort')){
            if($request->get('sort') == 'latest'){
                $products = $products->orderBy('id','DESC');

            }else if($request->get('sort') == 'price_asc'){
                $products = $products->orderBy('price','ASC');
            }else{
                $products = $products->orderBy('price','DESC');
            }
        }else{
            $products = $products->orderBy('id','DESC');
        }

        if(!empty($request->get('search'))){
            $products  = $products->where('title','like','%'.$request->get('search').'%');

        }

        $priceMax =  (intval($request->get('price_max')) == 0) ? 2000 : intval($request->get('price_max'));
        $priceMin = intval($request->get('price_min'));
        $sort = $request->get('sort');



        $products = $products->paginate(6);

        return view("front.shop",compact("categories", "products",'categorySelected','subCategorySelected','priceMax','priceMin','sort'));
    }


    public function product($slug)
    {
        $product = Product::where('slug', $slug)
        ->withCount('productRatings')
        ->withSum('productRatings','rating')
        ->with(['images','productRatings'])
        ->with('images')->first();


        if (!$product) {
            abort(404);
        }

        $allProducts = Product::where('id', '!=', $product->id)
        ->where('status', 1)
        ->where('category_id',$product->category_id)
        ->orWhere('sub_category_id',$product->sub_category_id)
        ->get();


        // Query for all products except the current one

        // Collect descriptions and slugs of all products
        $allProductDescriptions = $allProducts->pluck('description')->all();
        $allProductSlugs = $allProducts->pluck('slug')->all();

        // Extract features of the current product
        $currentFeatures = [
            $product->price,
            // tfidf($product->description, $allProductDescriptions),
            // tfidf($product->slug, $allProductSlugs),
        ];

        // Calculate cosine similarity with each product
        $similarProducts = [];
        foreach ($allProducts as $otherProduct) {
            $otherFeatures = [
                $otherProduct->price,
                // tfidf($otherProduct->description, $allProductDescriptions),
                // tfidf($otherProduct->slug, $allProductSlugs),
            ];

            $similarity = euclideanDistance($currentFeatures, $otherFeatures);

            $similarProducts[] = [
                'product' => $otherProduct,
                'similarity' => $similarity,
            ];
        }

        // Sort by similarity in descending order
        usort($similarProducts, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        // Take the top 4 similar products
        $similarProducts = array_slice($similarProducts, 1, 4);

        //Rating Calculation
        $avgRating = '0.00';
        if($product->product_ratings_count > 0){
            $avgRating = number_format(($product->product_ratings_sum_rating/$product->product_ratings_count),2);

        }

        return view('front.product', compact('product', 'similarProducts','avgRating'));
    }

    public function saveRating(Request $request, $id){
        $validation = Validator::make($request->all(), [
            'username'=> 'required|min:3',
            'email'=> 'required|email',
            'rating'=> 'required|numeric|min:1|max:5',
            'comment'=> 'required|max:250',
        ]);

        if($validation->fails()){
            return response()->json([
                'errors'=> $validation->errors(),
                'status' => false
            ]);

        }else{
            $productRating = new ProductRating();
            $productRating->product_id = $id;
            $productRating->username = $request->username;
            $productRating->email = $request->email;
            $productRating->comment = $request->comment;
            $productRating->rating = $request->rating;
            $productRating->status = 0;
            $productRating->save();

            $message = 'Thanks for your rating';
            session()->flash('success', $message);

            return response()->json([
                'message'=> $message,
                'status' => true
            ]);
        }

    }


}
