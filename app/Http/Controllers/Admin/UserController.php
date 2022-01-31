<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->type;
        $data = User::orderBy('id', 'DESC')->where('id', '!=', 1)->where('role', '=', $type)->paginate(10);
        return view('admin/user/index', ['data' => $data, 'type' => $type]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/user/create');
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
        $type = $request->user_type;
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'email',
            'phone' => 'required|min:10|numeric',
            // 'password' => 'required|min:6',
            // 'confirm_password' => 'required|same:password|min:6',
            'user_type' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $data = User::make();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->role = $request->user_type;
            $data->phone = $request->phone;
            // $data->password = Hash::make($request->password);
            $data->save();


            DB::commit();
            return redirect()->route('user.index', ['type' => $type])->withSuccess('User created successfully');
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
        $data = User::find($id);

        return view('admin/user/edit', ['data' => $data]);
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
        $type = $request->user_type;
        //
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'email',
            'phone' => 'required|min:10|numeric',
            // 'password' => 'required|min:6',
            // 'confirm_password' => 'required|same:password|min:6',
            'user_type' => 'required'
        ]);

        try {
            DB::beginTransaction();

            $data = User::findOrFail($id);
            $data->name = $request->name;
            $data->email = $request->email;
            $data->role = $request->user_type;
            $data->phone = $request->phone;
            $data->status = $request->status;

            // if (!Hash::check($request->get('password'), $data->password) && $request->get('password') != '') {
            //     $data->password = Hash::make($request->get('password'));
            // }
            $data->save();


            DB::commit();
            return redirect()->route('user.index', ['type' => $type])->withSuccess('User updated successfully');
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
        //
        $user = User::findOrFail($id);
        $user->delete();

        return back()->withSuccess('User deleted successfully');
    }

    public function updatePassword(Request $request)
    {
        $input = $request->all();
        if ($input) {
            $validatedData = $request->validate([
                'currentPassword' => 'required',
                'newPassword' => 'required|min:8',
                'confirmPassword' => 'required|same:newPassword',
            ]);
            try {

                $user = User::find(Auth::User()->id);
                if (Hash::check($request->get('currentPassword'), $user->password)) {
                    $user->password = Hash::make($request->get('newPassword'));
                    $user->save();

                    return back()->with('success', 'Admin Password change successfully');
                }
                return back()->withErrors('Old password not match')->withInput($request->all());
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage())->withInput($request->all());
            }
        }

        return view('admin/change-password');
    }

    // get admin profile
    public function  profile(Request $request)
    {
        $input = $request->all();
        if ($input) {
            $validatedData = $request->validate([
                'name'  => 'required',
                'email'  => 'required|email'
            ]);
            try {

                $user = User::findOrFail(Auth::User()->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();

                return back()->with('success', 'Profile updated successfully');
            } catch (\Exception $e) {
                return back()->withErrors($e->getMessage());
            }
        }

        return view('admin/profile');
    }

    public function Availability()
    {
        $data = Availability::all();
        return view('admin/availability/index',compact('data'));
    }
    public function CreateAvailability()
    {
        return view('admin/availability/create');
    }

    public function PostCreateAvailability(Request $request)
    {

        $validatedData = $request->validate([
            'days' => 'required',
        ]);
        foreach ($request->days as $day) {
            $availability = Availability::where('days', $day)->first();
            if ($availability) {
                $availability->morning_time = serialize($request->morning_time);
                $availability->afternoon_time = serialize($request->afternoon_time);
                $availability->evening_time = serialize($request->evening_time);
                $availability->save();
               
            } else {
                $availability = Availability::make();
                $availability->days = $day;
                $availability->morning_time = serialize($request->morning_time);
                $availability->afternoon_time = serialize($request->afternoon_time);
                $availability->evening_time = serialize($request->evening_time);
                $availability->save();
                
            }
        }
        return redirect()->route('availability')->withSuccess('slot added successfully');
     
    }

    public function DeleteAvailability($id){
        $availability = Availability::findOrFail($id);
        $availability->delete();

        return back()->withSuccess('deleted successfully');
    }
}
