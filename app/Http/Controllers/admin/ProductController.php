<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'subCategory','images']);

        if ($request->get('keyword')) {
            $products = $products->where('products.title', 'like', '%' . $request->get('keyword') . '%');
        }

        $products = $products->paginate(10);

        return view("admin.products.list", compact('products'));
    }

    public function create(){
        $categories = Category::orderBy('name','ASC')->get();
        $subCategories = [];

        return view("admin.products.create", compact('categories','subCategories'));
    }

    public function store(Request $request){

        $rules =  [
            'title' => 'required|string|max:255',
             'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('measurement_unit', request('measurement_unit'))
                        ->where('measurement_value', request('measurement_value'));
                }),
            ],
            'description' => 'nullable|string|max:60000',
            'short_description' => 'nullable|string|max:10000',
            'shipping_returns' => 'nullable|string|max:60000',
            'measurement_unit' => 'required|in:gm,ml,l,kg,mg,gal,oz,floz,lb',
            'measurement_value' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'is_featured' => 'required|in:Yes,No',
            'sku' => 'required|string|max:255|unique:products',
            'barcode' => 'nullable|string|max:255',
            'track_qty' => 'required|in:Yes,No',
            'status' => 'required|in:0,1',
        ] ;


        if (!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()){
            $product = Product::create(
                $request->only(
                    'title',
                    'slug',
                    'description',
                    'short_description',
                    'shipping_returns',
                    'measurement_unit',
                    'measurement_value',
                    'price',
                    'compare_price',
                    'category_id',
                    'sub_category_id',
                    'is_featured',
                    'sku',
                    'barcode',
                    'track_qty',
                    'qty',
                    'status',
                )
            );
            $productId = $product->id;


            //Save Gallery Pics
            if(!empty($request->image_array)){
                foreach ($request->image_array as $temp_image_id){

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.',$tempImageInfo->name);
                    $ext = last($extArray); //like jpg, gif, png, jpeg

                    $productImage = new ProductImage();
                    $productImage->product_id = $productId;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $productId.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    //Generate Product Thumbnail
                    //Large Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destinationPath = public_path().'/uploads/products/large/'.$imageName;
                    $image = ImageManager::gd()->read($sourcePath);
                    $image->scale(height:1400);
                    $image->save($destinationPath);

                    //Small Image
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destinationPath = public_path().'/uploads/products/small/'.$imageName;
                    $image = ImageManager::gd()->read($sourcePath);
                    $image->resize(300,300);
                    $image->save($destinationPath);

                }

                foreach($request->image_array as $image_id_to_delete){
                    $deleteRequest = new Request([
                        'id' => $image_id_to_delete,
                    ]);

                    // Instantiate the TempImagesController
                    $tempImagesController = new TempImagesController();

                    // Call the delete function with the new request
                    $tempImagesController->delete($deleteRequest);
                }

            }

            session()->flash('success','Product created successfully');

            return response()->json([
                'status' => true,
                'message'=> 'Product created successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }


    }


    public function edit($productId, Request $request){

        $product = Product::find($productId);

        if(empty($product)){
            session()->flash('error','Product not found');

            return redirect()->route('products.index');
        }

        $images = $product->images;

        $categories = Category::orderBy('name','ASC')->get();
        $subCategories = $product->category->subCategories;

        return view("admin.products.edit", compact('categories','subCategories', 'product','images'));
    }

    public function update($productId, Request $request){
        $product = Product::find($productId);
        if(empty($product)){
            session()->flash('error','Product not found');


            return redirect()->route('products.index')->with('error','Product not found');
        }


        $rules =  [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id . ',id,measurement_value,' . $request->measurement_value . ',measurement_unit,' . $request->measurement_unit,
            'description' => 'nullable|string|max:60000',
            'short_description' => 'nullable|string|max:10000',
            'shipping_returns' => 'nullable|string|max:60000',
            'measurement_unit' => 'required|in:gm,ml,l,kg,mg,gal,oz,floz,lb',
            'measurement_value' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'is_featured' => 'required|in:Yes,No',
            'sku' => 'required|string|max:255|unique:products,sku,'.$product->id.',id',
            'barcode' => 'nullable|string|max:255',
            'track_qty' => 'required|in:Yes,No',
            'status' => 'required|in:0,1',
        ] ;


        if (!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(),$rules);

        if ($validator->passes()){
            $product->update(
                $request->only(
                    'title',
                    'slug',
                    'description',
                    'short_description',
                    'shipping_returns',
                    'measurement_unit',
                    'measurement_value',
                    'price',
                    'compare_price',
                    'category_id',
                    'sub_category_id',
                    'is_featured',
                    'sku',
                    'barcode',
                    'track_qty',
                    'qty',
                    'status',
                )
            );

            session()->flash('success','Product updated successfully');

            return response()->json([
                'status' => true,
                'message'=> 'Product updated successfully.'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }


    }

    public function destroy($productId, Request $request){
        $product = Product::find($productId);

        if(empty($product)){
            session()->flash('error','Product not found');
            return response()->json([
                'status'=> false,
                'notFound'=> true,
                'error'=> 'Product not found'
            ]);
        }

        $images = $product->images;

        if (!empty($images)){
            foreach ($images as $image){
                $filename = $image->image;
                $basePath = public_path('uploads/products/'); // adjust the path based on your folder structure

                // Delete the large image
                $imagePath = $basePath.'large/'.$filename;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Delete the small image
                $smallPath = $basePath . 'small/' . $filename;
                if (file_exists($smallPath)) {
                    unlink($smallPath);
                }
            }
            $product->images()->delete();
        }

        $product->delete();
        session()->flash('success','Product deleted successfully');
        return response()->json([
            'status'=> true,
            'message'=> 'Product Deleted Successfully'
        ]);
    }

    public function productRatings(Request $request){

        $ratings = ProductRating::select('product_ratings.*','products.title as productTitle')->orderBy('product_ratings.created_at','desc');

        $ratings = $ratings->leftJoin('products','products.id','product_ratings.product_id');

        if ($request->get('keyword')) {
            $ratings = $ratings->where('products.title', 'like', '%' . $request->get('keyword') . '%');
            $ratings = $ratings->orWhere('product_ratings.username', 'like', '%' . $request->get('keyword') . '%');

        }

        $ratings= $ratings->paginate(10);
        return view('admin.products.ratings', compact('ratings'));
    }

    public function changeRatingStatus(Request $request){
        $rating = ProductRating::find($request->id);
        $rating->status = $request->status;
        $rating->save();

        $message = 'Rating Status changed successfully!';
        session()->flash('success', $message);

        return response()->json([
            'status'=> true,
            'message'=> $message
        ]);


    }
}
