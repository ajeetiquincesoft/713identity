<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\DB;
class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Template::orderBy('id','DESC')->paginate(10);
        return view('admin/template/index',['data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $emailType=array('1'=>'registration success','2'=>'Email verify','3'=>'Forgot Password','4'=>'OTP Verify');
        return view('admin/template/create',['type'=>$emailType]);
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
            'type' => 'required',
            'content' => 'required',            
			'status'=>'required'
        ]);

        try{
            DB::beginTransaction();
			

            $data = Template::make();
            $data->type = $request->type;           
            $data->content = $request->content;            
            $data->status = $request->status;           
            $data->save();


            DB::commit();
            return redirect()->route('template.index')->withSuccess('Template created successfully');

        }catch (\Exception $e){
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
        $data = Template::find($id);
		$emailType=array('1'=>'registration success','2'=>'Email varify','3'=>'Forgot Password','4'=>'OTP Verify');
        return view('admin/template/edit',['data'=>$data,'type'=>$emailType]);
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
        'type' => 'required',
        'content' => 'required',            
        'status'=>'required'
    ]);

    try{
        DB::beginTransaction();
        
        $data = Template::findOrFail($id);
        $data->type = $request->type;            
        $data->content = $request->content;         
        $data->status = $request->status;
        $data->update();


        DB::commit();
        return redirect()->route('template.index')->withSuccess('Template updated successfully');

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
        $data = Template::findOrFail($id);
        $data->delete();

        return back()->withSuccess('Page deleted successfully');
    }
}
