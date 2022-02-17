<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Article::orderBy('id', 'DESC')->paginate(10);
        return view('admin/article/index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/article/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $slug = null;
            if (isset($request->title) && !empty($request->title)) {
                $slug = preg_replace("/-$/", "", preg_replace('/[^a-z0-9]+/i', "-", strtolower($request->title)));
            }

            if (!empty($slug) && $slug != null) {
                $is_slug_exist = Article::where('slug', $slug)->first();
                if ($is_slug_exist) {
                    return redirect()->route('article.create')->with('Slug already exists.');
                }
            }

            $data = Article::make();
            $data->title = $request->title;
            $data->slug = $slug;
            $data->content = $request->content;
            $data->meta_data = $request->meta_data;
            $data->meta_description = $request->meta_description;
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('article.index')->withSuccess('Created Successfully');
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
        $data = Article::find($id);

        return view('admin/article/edit', ['data' => $data]);
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
            'title' => 'required',
            'content' => 'required',
            'status' => 'required'
        ]);
        try{
            DB::beginTransaction();
			$slug = null;
			 if(isset($request->title) && !empty( $request->title)){
            $slug = preg_replace("/-$/","",preg_replace('/[^a-z0-9]+/i', "-", strtolower( $request->title)));
			}

			/* if(!empty($slug) && $slug != null){
				$is_slug_exist = page::where('slug', $slug)->first();
				if($is_slug_exist){
					return redirect()->route('page.create')->with('Slug already exists.');
				}
			} */
            $data = Article::findOrFail($id);
			$data->title = $request->title;
            $data->content = $request->content;
            $data->meta_data = $request->meta_data;
            $data->meta_description = $request->meta_description;
            $data->status = $request->status;
            $data->update();


            DB::commit();
            return redirect()->route('article.index')->withSuccess('Updated successfully');

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
        $page = Article::findOrFail($id);
        $page->delete();

        return back()->withSuccess('Deleted successfully');
    }
}
