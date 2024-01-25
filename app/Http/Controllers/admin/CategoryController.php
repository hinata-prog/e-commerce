<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Intervention\Image\ImageManager;

class CategoryController extends Controller
{
    public function index(Request $request){
        $categories = Category::latest();
        if($request->get('keyword')){
            if (!empty($request->get('keyword'))) {
                $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
            }
        }

        $categories = $categories->paginate(10);

        return view("admin.category.list", compact('categories'));
    }

    public function create() {
        return view("admin.category.create");
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name"=> "required",
            "slug"=> "required|unique:categories",
            "status"=>"required",
            "showHome"=> "required",
        ]);

        if ($validator->passes()) {

            $category = Category::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                'showHome' => $request->showHome,
            ]);

            //Save Image Here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);

                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path() .'/temp/'. $tempImage->name;
                $dPath = public_path() .'/uploads/category/'. $newImageName;
                File::copy($sPath,$dPath);

                //Generate image thumbnail
                $dPathThumbnail = public_path() .'/uploads/category/thumb/'. $newImageName;
                // create new image instance
                $img = ImageManager::gd()->read($sPath);

                // resize to 450 x 600 pixel
                $img->resize(450, 600);
                $img->save($dPathThumbnail);

                $category->image = $newImageName;
                $category->save();

            }
            

            session()->flash("success","Category added successfully");

            return response()->json([
                "status"=> true,
                "message"=> 'Category added successfully'
            ]);

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request) {

        $category = Category::find($categoryId);
        if(empty($category)){
            session()->flash('error','Category not found');

            return redirect()->route('categories.index');
        }


        return view('admin.category.edit',compact('category'));
    }

    public function update($categoryId, Request $request){
        $category = Category::find($categoryId);
        if(empty($category)){
            session()->flash('error','Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            "name"=> "required",
            "slug" => "required|unique:categories,slug," . $category->id . ",id",
            "status"=>"required",
            "showHome" => "required"
        ]);

        if ($validator->passes()) {


            $category->update([
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
                'showHome' => $request->showHome,
            ]);

            //Save Image Here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);

                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path() .'/temp/'. $tempImage->name;
                $dPath = public_path() .'/uploads/category/'. $newImageName;
                File::copy($sPath,$dPath);

                //Generate image thumbnail
                $dPathThumbnail = public_path() .'/uploads/category/thumb/'. $newImageName;
                $img = ImageManager::gd()->read($sPath);

                // resize to 300 x 200 pixel
                $img->resize(450, 600);
                $img->save($dPathThumbnail);

                $category->image = $newImageName;
                $category->save();
            }

           

            session()->flash("success","Category updated successfully");

            return response()->json([
                "status"=> true,
                "message"=> 'Category updated successfully'
            ]);

        }else{
            return response()->json([
                "status"=> false,
                "errors"=> $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request){
        $category =  Category::find($categoryId);
        if(empty($category)){
            session()->flash('error','Category not found');
            return response()->json([
                'status' => false,
                'message' => 'Category not fpund'
            ]);
        }

        // Check if category has an existing image
        if (!empty($category->image)) {
            $basePath = public_path('uploads/category/'); // adjust the path based on your folder structure

                // Delete the large image
                $imagePath = $basePath . $category->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                // Delete the small image
                $smallPath = $basePath . 'thumb/' . $category->image;
                if (file_exists($smallPath)) {
                    unlink($smallPath);
                }
        }

        $category->delete();

        session()->flash('success','Category deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);

    }
}
