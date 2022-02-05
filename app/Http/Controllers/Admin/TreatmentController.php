<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Treatment;
use App\Models\TreatmentOption;
use App\Models\TreatmentOptionPackage;
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
		
		
       
        $validatedData = $request->validate([
            'title' 				=> 'required',
            'category' 				=> 'required',
            'status' 				=> 'required',
			/* 'option_title'			=> 'required'
			'option_image'			=> 'required',
			'option_status'			=> 'required',
			'option_package_type'	=> 'required',
			'option_subpackage'		=> 'required',
			'option_package_title'	=> 'required' */
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
		
					
			// options tables data inserting
				$boxcounter = $request->boxcounter;
				for($i=1; $i<=$boxcounter; $i++){
					$boxes = 'box'.$i;
					
					$options=$data->treatmentOption()->make();
					 $options->name=$request->$boxes['option_title'][0];
					 $options->status=$request->$boxes['option_status'][0];
					 $options->save();
					 $j=0;
					  $treatment_id=$data->id;
					 
					 foreach($request->$boxes['option_package_type'] as $package){
						 //echo $request->$boxes['option_subpackage'][$j];
						$optionspackage=TreatmentOptionPackage::make();
						$optionspackage->treatment_id =$treatment_id;
						$optionspackage->treatmentoption_id =$options->id;
						$optionspackage->small_name =$request->$boxes['option_subpackage'][$j];
						$optionspackage->name =$request->$boxes['option_package_title'][$j];
						$optionspackage->packagetype =$request->$boxes['option_package_type'][$j];
						$optionspackage->price =$request->$boxes['option_package_price'][$j];
						$optionspackage->max =$request->$boxes['option_package_max'][$j];
						$optionspackage->min =$request->$boxes['option_package_min'][$j];
						 $optionspackage->save();
						 $j++;
						}
					 	
				}
			// ends
			
			
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
