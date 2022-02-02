<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unavailability;
use Illuminate\Http\Request;

class UnavailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Unavailability::orderBy('id', 'DESC')->paginate(10);
        return view('admin/unavailability/index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $time_slotes = array('08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30');
        return view('admin/unavailability/create', compact('time_slotes'));
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
            'date' => 'required',
        ]);
        $unavailability = Unavailability::where('date', $request->date)->first();
        if ($unavailability) {
            $unavailability->time = ($request->time) ? serialize($request->time) : '';
            if (!$request->full_day) {

                $unavailability->time = ($request->time) ? serialize($request->time) : '';
            } else {
                $unavailability->time = '';
            }
            $unavailability->full_day = $request->full_day;
            $unavailability->save();
        } else {
            $unavailability = Unavailability::make();
            $unavailability->date = $request->date;
            if (!$request->full_day) {

                $unavailability->time = ($request->time) ? serialize($request->time) : '';
            } else {
                $unavailability->time = '';
            }
            $unavailability->full_day = $request->full_day;
            $unavailability->save();
        }
        return redirect()->route('unavailability.index')->withSuccess('added successfully');
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
        $time_slotes = array('08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30');

        $data = Unavailability::find($id);
        return view('admin/unavailability/edit', compact('data','time_slotes')); 
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
        $data = Unavailability::findOrFail($id);
        $data->delete();

        return back()->withSuccess('deleted successfully');
    }
}
