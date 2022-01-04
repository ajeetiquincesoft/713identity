<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Treatment;
use Illuminate\Support\Facades\DB;
class TreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Treatment::orderBy('id', 'DESC')->paginate(10);
        return view('admin/treatment/index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::whereStatus(1)->get();
        return view('admin/treatment/create', ['categories' => $categories]);
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
            'title' => 'required',
            'category' => 'required',
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $slug = null;
            if (isset($request->title) && !empty($request->title)) {
                $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($request->title)));
            }

            if (!empty($slug) && $slug != null) {
                $is_slug_exist = Treatment::where('slug', $slug)->first();
                if ($is_slug_exist) {
                    return redirect()->route('page.create')->with('Slug already exists.');
                }
            }

            $data = Treatment::make();
            $data->title = $request->title;
            $data->slug = $slug;
            $data->category_id   = $request->category;
            $data->short_description = $request->short_description;
            $data->description = $request->description;
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
        //
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
    }
}
