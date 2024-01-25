<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;


class BannerImageController extends Controller
{
    public function index(Request $request)
    {
        $bannerImages = BannerImage::orderBy('created_at', 'desc');

        if ($request->get('keyword')) {
            $bannerImages = $bannerImages->where('images.id', 'like', '%' . $request->get('keyword') . '%');
        }

        $bannerImages = $bannerImages->paginate(10);

        return view("admin.banner-images.list", compact('bannerImages'));
    }

    public function create()
    {
        return view("admin.banner-images.create");
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_array' => 'required|array',
        ]);

        if ($validator->passes()) {
            foreach ($request->image_array as $temp_image_id) {
                $tempImageInfo = TempImage::find($temp_image_id);

                $extArray = explode('.', $tempImageInfo->name);
                $ext = last($extArray); // like jpg, gif, png, jpeg

                $bannerImage = new BannerImage();
                $uuid = Str::uuid();;

                $imageName = $uuid . '-' . time() . '.' . $ext;
                $bannerImage->image = $imageName;
                $bannerImage->save();

                // Generate Product Thumbnail
                // Large Image
                $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                $destinationPath = public_path() . '/uploads/banner/large/' . $imageName;
                $image = ImageManager::gd()->read($sourcePath);             $image->scale(1300);
                $image->save($destinationPath);

                // Small Image
                $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                $destinationPath = public_path() . '/uploads/banner/small/' . $imageName;
                $image = ImageManager::gd()->read($sourcePath);                $image->scale(760);
                $image->save($destinationPath);
            }

          

            session()->flash('success', 'Product created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy(Request $request,$id)
    {
        $imageId = $id;
        // Find the image in the database
        $image = BannerImage::where('id',$imageId)->first();
        if (empty($image)) {
            session()->flash('error', 'Image not found');

            return response()->json([
                'status' => false,
                'message' => 'Image not found',
            ]);
        }
        $filename = $image->image;

        $basePath = public_path('uploads/banners/'); // adjust the path based on your folder structure

        // Delete the large image
        $imagePath = $basePath . 'large/' . $filename;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Delete the small image
        $smallPath = $basePath . 'small/' . $filename;
        if (file_exists($smallPath)) {
            unlink($smallPath);
        }

        // Delete the image record from the database
        $image->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }
}
