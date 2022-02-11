<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Treatment;
use App\Models\User;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\AppointmentPackages;
use App\Models\QuestionAnswer;
use App\Models\TreatmentOptionPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use JWTAuth;
use Stripe;

class ApiController extends Controller
{
    public function loginAndRegister(Request $request)
    {
        // dd($request->all());
        if ($request->otp) {
            $credentials = $request->only('phone', 'otp');
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'otp' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
            $credentials['status'] = 1;
            try {
                if (!$token = auth('api')->attempt($credentials)) {


                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid otp.',

                    ], 400);
                }

                /* if($token){
                    $user = JWTAuth::authenticate($token);
                } */
            } catch (JWTException $e) {
                return $credentials;
                return response()->json([
                    'success' => false,
                    'message' => 'Could not create token.',
                ], 500);
            }

            //Token created, return with success response and jwt token


            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        } else {


            $validator = Validator::make($request->all(), [
                'phone' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
            $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
            $authToken  = config('app.twilio')['TWILIO_AUTH_TOKEN'];
            $client = new Client($accountSid, $authToken);
            $user = User::wherePhone($request->phone)->first();
            if ($user) {
                $code = $user->generateCodeNumber();
                $user->name = $request->phone;
                $user->phone = $request->phone;
                $user->otp = $code;
                $user->otp_expire = Carbon::now()->addMinutes(10);
                $user->save();
                try {
                    $client->messages->create(
                        $user->phone,
                        array(
                            'from' => config('app.twilio')['TWILIO_NUMBER'],
                            'body' => "Your otp " . $code
                        )
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Otp has been sent.'
                    ]);
                } catch (\Exception $ex) {
                    return response()->json([
                        'success' => false,
                        'message' => $ex->getMessage(),
                    ]);
                }
            } else {

                $data = User::make();
                $code = $data->generateCodeNumber();
                $data->name = $request->phone;
                $data->phone = $request->phone;
                $data->otp = $code;
                $data->otp_expire = Carbon::now()->addMinutes(10);
                $data->save();
                try {
                    $client->messages->create(
                        $request->phone,
                        array(
                            'from' => config('app.twilio')['TWILIO_NUMBER'],
                            'body' => "Your otp " . $code
                        )
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Otp has been sent.'
                    ]);
                } catch (\Exception $ex) {
                    return response()->json([
                        'success' => false,
                        'message' => $ex->getMessage(),
                    ]);
                }
            }
        }
    }

    public function get_user(Request $request)
    {

        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = Auth('api')->authenticate($request->token);

        return response()->json(['user' => $user]);
    }

    public function logout(Request $request)
    {

        // dd($request->all());
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout        
        try {
            auth('api')->invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function updateProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'name' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->profile_pic) {
                $frontimage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->profile_pic));
                $profile_pic = time() . '.jpeg';
                // $new_path = Storage::disk('public')->put($profile_pic, $frontimage);
                file_put_contents($profile_pic, $frontimage);
                $user->profile_pic = $profile_pic;
            }
            $user->save();
            return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }

    public function getPopularTreatment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $treatment = Treatment::with(['treatmentOption', 'treatmentOption.treatmentOptionPackage', 'category'])->where('popular', 1)->where('status', 1)->paginate(100);

            return response()->json(['success' => true, 'message' => 'popular treatments', 'treatment' => $treatment]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }
    public function getTreatment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $treatment = Treatment::with(['treatmentOption', 'treatmentOption.treatmentOptionPackage', 'category'])->where('status', 1)->paginate(20);

            return response()->json(['success' => true, 'message' => 'treatments', 'treatment' => $treatment]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }

    public function getCategoryWithTreatment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $category = Category::with(['treatment', 'treatment.treatmentOption', 'treatment.treatmentOption.treatmentOptionPackage'])->where('status', 1)->get();
            return response()->json(['success' => true, 'message' => 'Category treatments', 'category' => $category, 'wishlist' => $user->wishlist()->get()]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }

    public function getAvailability(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'day' => 'required',
            'date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $data = Availability::where('status', 1)->where('days', strtolower($request->day))->first();
            if ($data) {
                $morning_slot = ($data->morning_time) ? unserialize($data->morning_time) : [];
                foreach ($morning_slot as $slot) {
                    $morning[] = array('time' => $slot);
                }
                $afternoon_slot = ($data->afternoon_time) ? unserialize($data->afternoon_time) : [];
                foreach ($afternoon_slot as $slot) {
                    $afternoon[] = array('time' => $slot);
                }
                $evening_slot = ($data->evening_time) ? unserialize($data->evening_time) : [];
                foreach ($evening_slot as $slot) {
                    $evening[] = array('time' => $slot);
                }
                return response()->json(['success' => true, 'message' => 'Availability', 'day' => $data->days, 'morning_slot' => $morning, 'afternoon_slot' => $afternoon, 'evening_slot' => $evening]);
            } else {
                return response()->json(['success' => false, 'message' => 'No Availability']);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }
    public function getWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $wishlists = $user->wishlist()->with(['treatment', 'treatment.treatmentoption', 'user'])->get();
            return response()->json(['success' => true, 'message' => 'wishlist', 'wishlists' => $wishlists]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }
    public function addWishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'treatment_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $wishlists = $user->wishlist()->where('treatment_id', $request->treatment_id)->first();
            if ($wishlists) {
                $wishlists->delete();
                return response()->json(['success' => true, 'message' => 'Removed from wishlist']);
            } else {
                $wishlists = $user->wishlist()->make();
                $wishlists->treatment_id = $request->treatment_id;
                $wishlists->save();
                return response()->json(['success' => true, 'message' => 'Added to wishlist']);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }

    public function couponVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'treatment_id' => 'required',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $coupon = Coupon::whereCode($request->code)->whereStatus(1)->first();
            if ($coupon) {
                if ($coupon->coupon_for == 'specific') {
                    if ($coupon->treatment_id == $request->treatment_id) {
                        return response()->json([
                            'success' => true,
                            'message' => 'Coupon code is valid.',
                            'validate' => true,
                            'coupon' => $coupon
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid coupon code.',
                            'validate' => false,
                            'coupon' => array()
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Coupon code is valid.',
                        'validate' => true,
                        'coupon' => $coupon
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid coupon code.',
                    'validate' => false,
                    'coupon' => array()
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }

    public function BookAppointment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'treatment_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'total' => 'required',
            'discounted_total' => 'required',
            'discount_applied' => 'required',
            'packages' => 'required',
            'stripe_token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        // return response()->json([
        //     'success' => true,
        //     'message' => $request->all(),
        //     'message2' => $request->packages[0],
        //     'message3' => $request->packages[1],
        // ]);
        if ($user) {

            Stripe\Stripe::setApiKey(config('app.stripe_test') ? config('app.stripe_key') : config('app.stripe_key'));
            $amount = ($request->discounted_total * 100);
            $pay = Stripe\Charge::create([
                "amount" => $amount,
                "currency" => "USD",
                "source" => $request->stripe_token,
                "description" => "test",
            ]);
            $customerpay = Payment::make();
            $customerpay->user_id = $user->id;
            $customerpay->amount = $pay->amount / 100;
            $customerpay->transaction_id = $pay->balance_transaction;
            $customerpay->status = $pay->paid;
            $customerpay->payment_date = date('Y-m-d H:i:s', $pay->created);
            $customerpay->save();
            $payment_id = $customerpay->id;
            if ($pay->balance_transaction) {
                $appointment = Appointment::make();
                $appointment->user_id = $user->id;
                $appointment->treatment_id = $request->treatment_id;
                $appointment->date =  $request->date;
                $appointment->time =  $request->time;
                $appointment->total = $request->total;
                $appointment->discounted_total = $request->discounted_total;
                $appointment->discount_applied = $request->discount_applied;
                $appointment->discount_coupon_code = $request->discount_coupon_code;
                $appointment->payment_id = $payment_id;
                $appointment->save();
                $appointment_id = $appointment->id;
                foreach ($request->packages as $package) {
                    $package_data = TreatmentOptionPackage::find($package);
                    $optiion_id = $package_data->treatmentoption_id;
                    $appointent_packages = $appointment->appointmentPackages()->make();
                    $appointent_packages->treatmentoption_id = $optiion_id;
                    $appointent_packages->treatmentoptionpackage_id = $package;
                    $appointent_packages->save();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Your appointment has been schedule successfully.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Your payment has been failed.Please try again or contact to admin.',
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }

    public function GetAppointment(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $appointments = $user->appointments()->with('appointmentPackages','appointmentPayment','appointmentPackages.treatmentOptionPackage')->get();
            return response()->json(['success' => true, 'message' => 'question answer', 'appointments' => $appointments]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }
    public function QuestionAnswer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }
        $user = auth('api')->authenticate($request->token);
        if ($user) {
            $questionanswers = QuestionAnswer::whereStatus(1)->get();
            return response()->json(['success' => true, 'message' => 'question answer', 'questionanswers' => $questionanswers]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Token is not valid. please contact to the admin.',
            ]);
        }
    }
}
