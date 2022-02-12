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
			'image' 				=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			
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
                    return redirect()->route('treatment.create')->with('Slug already exists.');
                }
            } 
			
			$TreatmentImage = time().'.'.$request->image->extension();  
			$request->image->move(public_path('admin_assets\treatment_images'), $TreatmentImage);
		
            $data = Treatment::make();
            $data->title = $request->title;
            $data->slug = $slug;
            $data->category_id   = $request->category;
            $data->short_description = $request->short_description;
            $data->description = $request->description;
            $data->status = $request->status;
			$data->image = $TreatmentImage;
            $data->save();
		
					
			// options tables data inserting
				$boxcounter = $request->boxcounter;
				for($i=1; $i<=$boxcounter; $i++){
					$boxes = 'box'.$i;
					$TreatmentoptionImage = time().'.'.$request->$boxes['option_image'][0]->extension(); 
					$request->$boxes['option_image'][0]->move(public_path('admin_assets\treatment_images'), $TreatmentoptionImage);
					
					
					$options=$data->treatmentOption()->make();
					 $options->name=$request->$boxes['option_title'][0];
					 $options->image=$TreatmentoptionImage;
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
            return redirect()->route('treatment.index')->withSuccess('created successfully');
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
		
        $categories = Category::whereStatus(1)->get();
		$treatments = Treatment::whereStatus(1)->where('id', '!=', $id)->get();
		$data = Treatment::find($id);
		$treatmentsoptions = TreatmentOption::whereTreatment_id($id)->where('status', '=', 1)->get();
		$treatmentoptionspackage=TreatmentOptionPackage::whereTreatment_id($id)->get();
		 return view('admin/treatment/edit', ['categories'=>$categories, 'data' => $data, 'treatment' => $treatments,'treatmentoptions'=>$treatmentsoptions,'treatmentsoptionspackage'=>$treatmentoptionspackage ]);
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
		//print_r($request->all());
	//die;
       $validatedData = $request->validate([
            'title' => 'required',
            'status' => 'required'
        ]);
        try{
            DB::beginTransaction();
			if($request->treatmentimagenew){
			$TreatmentImage = time().'.'.$request->image->extension();  
			$request->image->move(public_path('admin_assets\treatment_images'), $TreatmentImage);
				//echo "hello";
				unlink(public_path('admin_assets\treatment_images').$request->oldimage);
				
			}
			
            $data = Treatment::findOrFail($id);
			$data->title = $request->title;
            $data->category_id   = $request->category;
            $data->short_description = $request->short_description;
            $data->description = $request->description;
            $data->image = $TreatmentImage;
			$data->status = $request->status;
            $data->update();


            DB::commit();
            return redirect()->route('treatment.index')->withSuccess('Updated successfully');

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
       
    }
}
