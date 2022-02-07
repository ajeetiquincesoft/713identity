<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = QuestionAnswer::OrderBy('id', 'DESC')->paginate(10);
        return view('admin/questionAnswer/index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/questionAnswer/create');
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
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required'
        ]);
        try {
            DB::beginTransaction();

            $data = QuestionAnswer::make();
            $data->question = $request->question;
            $data->answer = $request->answer;
            $data->status = $request->status;
            $data->save();
            DB::commit();
            return redirect()->route('questionAnswer.index')->withSuccess('created successfully');
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
        $data=QuestionAnswer::find($id);
        return view('admin/questionAnswer/edit',compact('data'));
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
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required'
        ]); 
        try {
            DB::beginTransaction();

            $data = QuestionAnswer::find($id);
            $data->question = $request->question;
            $data->answer = $request->answer;
            $data->status = $request->status;
            $data->save();
            DB::commit();
            return redirect()->route('questionAnswer.index')->withSuccess('updated successfully');
        } catch (\Exception $e) {
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
        $data = QuestionAnswer::findOrFail($id);
        $data->delete();

        return back()->withSuccess('deleted successfully');
    }
}
