<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Category::orderBy('id', 'DESC')->paginate(10);
        return view('admin/category/index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereStatus(1)->get();
        return view('admin/category/create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $slug = null;
            if (isset($request->name) && !empty($request->name)) {
                $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($request->name)));
            }

            if (!empty($slug) && $slug != null) {
                $is_slug_exist = Category::where('slug', $slug)->first();
                if ($is_slug_exist) {
                    return redirect()->route('page.create')->with('Slug already exists.');
                }
            }

            $data = Category::make();
            $data->name = $request->name;
            $data->slug = $slug;
            $data->description = $request->description;
            $data->parent_id  = $request->parent_category;
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('category.index')->withSuccess('created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = Category::whereStatus(1)->where('id', '!=', $id)->get();
        $data = Category::find($id);

        return view('admin/category/edit', ['data' => $data, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);
        try{
            DB::beginTransaction();
			
            $data = Category::findOrFail($id);
			$data->name = $request->name;
            $data->description = $request->description; 
            $data->parent_id  = $request->parent_category;          
            $data->status = $request->status;
            $data->update();


            DB::commit();
            return redirect()->route('category.index')->withSuccess('Updated successfully');

        }catch (\Exception $e){
            DB::rollback();
            return back()->withErrors($e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         //
         $category = Category::findOrFail($id);
         $category->delete();
 
         return back()->withSuccess('deleted successfully');
    }
}
