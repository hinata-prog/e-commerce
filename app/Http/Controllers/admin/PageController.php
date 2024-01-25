<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request){
        $pages = Page::latest();
        if($request->get('keyword')){
            $pages = $pages->where('name','like','%'.$request->get('keyword').'%');
        }

        $pages = $pages->paginate(10);
        return view("admin.pages.list",compact("pages"));

    }
    public function create(){

        return view("admin.pages.create");

    }
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            "name"=> "required",
            "slug"=> "required|unique:pages,slug",
            'content' => 'max:100000',
            "include_nav" => 'required|in:1,0',
            "for_registered" => 'required|in:1,0',
            "show_in_header" => 'required|in:1,0',
            "show_in_footer" => 'required|in:1,0'


        ]);

        if($validator->fails()){
            return response()->json([
                "errors"=> $validator->errors(),
                'status' => false
            ]);
        }else{

            $page = Page::create($request->all());
            $message = 'Page added successfully!';
            session()->flash('success', $message);

            return response()->json([
                "message"=> $message,
                'status' => true
            ]);
        }

    }
    public function edit($id){
        $page = Page::find($id);

        if($page == null){
            $message = 'Page not found';
            session()->flash('error', $message);
            return redirect()->route('pages.index');
        }

        return view("admin.pages.edit", compact("page"));

    }
    public function update(Request $request, $id){
        $page = Page::find($id);

        if($page == null){
            $message = 'Page not found';
            session()->flash('error', $message);
            return redirect()->route('pages.index');
        }

        $validator = Validator::make($request->all(), [
            "name"=> "required",
            "slug"=> 'required|unique:pages,slug,'.$page->id.',id',
            'content' => 'max:100000',
            "include_nav" => 'required|in:1,0',
             "show_in_header" => 'required|in:1,0',
            "show_in_footer" => 'required|in:1,0'

        ]);

        if($validator->fails()){
            return response()->json([
                "errors"=> $validator->errors(),
                'status' => false
            ]);
        }else{

            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->include_nav = $request->include_nav;
            $page->for_registered = $request->for_registered;
            $page->show_in_header = $request->show_in_header;
            $page->show_in_footer = $request->show_in_footer;

            $page->save();

            $message = 'Page updated successfully!';
            session()->flash('success', $message);

            return response()->json([
                "message"=> $message,
                'status' => true
            ]);
        }

    }
    public function destroy($id){
        $page = Page::find($id);

        if($page == null){
            $message = 'Page not found';
            session()->flash('error', $message);
            return redirect()->route('pages.index');
        }
        $message = 'Page deleted successfully';
        $page->delete();

        session()->flash('success', $message);

        return response()->json([
            'status'=>true,
            'message'=> $message
        ]);
    }
}
