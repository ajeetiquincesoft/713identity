<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Treatment;
use App\Models\TreatmentOption;
use App\Models\TreatmentOptionPackage;
use Illuminate\Support\Facades\DB;
use QrCode;
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
            'treatment_type' 		=> 'required',
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
			
            $data = Treatment::make();
            $data->title = $request->title;
            $data->slug = $slug;
			$data->treatment_type = $request->treatment_type;
            $data->category_id   = $request->category;
            $data->short_description = $request->short_description;
            $data->description = $request->description;
            $data->status = $request->status;
			
			if ($request->hasFile('image')) {
                $data->image = $data->upload_image($request->image);
            } 
			
			
            $data->save();
		// add qr code for treatment
		  $treatment = Treatment::findOrFail($data->id);
		  $qrcode = \QrCode::format('png')
							 ->size(200)->errorCorrection('H')
							 ->generate($data->id,'storage/qrcode_'.$data->id.'.png');
			$treatment->qr_code='qrcode_'.$data->id.'.png';
			$treatment->update();
					
			// options tables data inserting
				$boxcounter = $request->boxcounter;
				for($i=1; $i<=$boxcounter; $i++){
					$boxes = 'box'.$i;
					$options=$data->treatmentOption()->make();
					 $options->name=$request->$boxes['option_title'][0];
					if (isset($request->$boxes['option_image'][0])) {
						 $options->image = $options->upload_image($request->$boxes['option_image'][0]);
					 } 
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
		//dd($categories);
		
		$treatments = Treatment::with('TreatmentOption','TreatmentOption.treatmentOptionPackage','category')->find($id);
	
		 return view('admin/treatment/edit', ['categories'=>$categories, 'data' => $treatments]);
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
	
       $validatedData = $request->validate([
            'title' 				=> 'required',
            'category' 				=> 'required',
            'status' 				=> 'required',
			
        ]);
        try{
            DB::beginTransaction();
			
            $data = Treatment::findOrFail($id);
			$data->title = $request->title;
            $data->category_id   = $request->category;
            $data->short_description = $request->short_description;
            $data->description = $request->description;
			if ($request->hasFile('treatmentimagenew')) {
                $data->image = $data->upload_image($request->treatmentimagenew);
            } 
			
			$data->status = $request->status;
            $data->update();
			
		 $boxcounter = $request->boxcounter[0];
	
			for($i=1; $i<=$boxcounter; $i++){
				
				$boxes = 'box'.$i;
			    $treatment_id=$id;
				
				if(@$request->$boxes['option_id'][0]){
					
				 $options=TreatmentOption::findOrFail($request->$boxes['option_id'][0]);
				 $options->name=$request->$boxes['option_title'][0];
				
				 if(isset($request->$boxes['option_imagenew'][0])) {
						 $options->image = $options->upload_image($request->$boxes['option_imagenew'][0]);
					 } 
				 $options->status=$request->$boxes['option_status'][0];
				 $options->update();
				  $j=0;
				 foreach($request->$boxes['option_package_type'] as $package){
					
					 if(isset($request->$boxes['option_package_id'][$j])){
							$optionspackage=TreatmentOptionPackage::findOrFail($request->$boxes['option_package_id'][$j]);
							$optionspackage->small_name =$request->$boxes['option_subpackage'][$j];
							$optionspackage->name =$request->$boxes['option_package_title'][$j];
							$optionspackage->packagetype =$request->$boxes['option_package_type'][$j];
							$optionspackage->price =$request->$boxes['option_package_price'][$j];
							$optionspackage->max =$request->$boxes['option_package_max'][$j];
							$optionspackage->min =$request->$boxes['option_package_min'][$j];
							 $optionspackage->update();
					 }elseif($request->$boxes['option_id'][0]){
							
							$optionspackage=TreatmentOptionPackage::make();
							$optionspackage->treatment_id =$treatment_id;
							$optionspackage->treatmentoption_id =$request->$boxes['option_id'][0];
							$optionspackage->small_name =$request->$boxes['option_subpackage'][$j];
							$optionspackage->name =$request->$boxes['option_package_title'][$j];
							$optionspackage->packagetype =$request->$boxes['option_package_type'][$j];
							$optionspackage->price =$request->$boxes['option_package_price'][$j];
							$optionspackage->max =$request->$boxes['option_package_max'][$j];
							$optionspackage->min =$request->$boxes['option_package_min'][$j];
							 $optionspackage->save(); 
						 
					 }
					 $j++;
					}
				   
				}else{
					
					$options_new=TreatmentOption::make();
					 $options_new->name=$request->$boxes['option_title'][0];
					if (isset($request->$boxes['option_image'][0])) {
						 $options_new->image = $options_new->upload_image($request->$boxes['option_image'][0]);
					 } 
					 $options_new->treatment_id=$treatment_id;  
					 $options_new->status=$request->$boxes['option_status'][0]; 
					 $options_new->save();
					 $j=0;
					 foreach($request->$boxes['option_package_type'] as $package){
						 //echo $request->$boxes['option_subpackage'][$j];
						$optionspackage=TreatmentOptionPackage::make();
						$optionspackage->treatment_id =$treatment_id;
						$optionspackage->treatmentoption_id =$options_new->id;
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
					
				
			}


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
      $treatment = Treatment::findOrFail($id);
      $treatment->delete();
       return back()->withSuccess('deleted successfully');
    }
}
