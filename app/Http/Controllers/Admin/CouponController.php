<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Coupon::with('Treatment')->orderBy('id', 'DESC')->paginate(10);       
        return view('admin/coupons/index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $treatments = Treatment::where('status', 1)->get();
        return view('admin/coupons/create', compact('treatments'));
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

        if ($request->coupon_for == 'specific') {
            $validatedData = $request->validate([
                'title' => 'required',
                'coupon_for' => 'required',
                'code' => 'required|unique:coupons',
                'discount' => 'required',
                'treatment' => 'required',
                'expiry_date' => 'required',
                'status' => 'required'
            ]);
        } else {
            $validatedData = $request->validate([
                'title' => 'required',
                'coupon_for' => 'required',
                'code' => 'required|unique:coupons',
                'discount' => 'required',
                'expiry_date' => 'required',
                'status' => 'required'
            ]);
        }


        try {
            DB::beginTransaction();

            $data = Coupon::make();
            $data->title = $request->title;
            $data->user_id = Auth::user()->id;
            $data->coupon_for   = $request->coupon_for;
            if ($request->coupon_for == 'specific') {
                $data->treatment_id   = $request->treatment;
            } else {
                $data->treatment_id   = 0;
            }
            $data->code   = $request->code;
            $data->discount = $request->discount;
            $data->expiry_date = Carbon::createFromFormat('d-m-Y', $request->expiry_date)->format('Y-m-d');
            $data->status = $request->status;
            $data->save();


            DB::commit();
            return redirect()->route('coupons.index')->withSuccess('created successfully');
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
        $data = Coupon::findOrFail($id);
        $data->delete();

        return back()->withSuccess('deleted successfully');
    }
}
