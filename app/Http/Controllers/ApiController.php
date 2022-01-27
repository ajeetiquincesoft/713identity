<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use JWTAuth;

class ApiController extends Controller
{
    public function loginAndRegister(Request $request)
    {
        // dd($request->all());
        if ($request->otp) {
            $credentials = $request->only('phone','otp');
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'otp' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }
           // $credentials['status'] = 1;
            try {
                if (!$token = auth('api')->attempt($credentials)) {


                    return response()->json([
                        'success' => false,
                        'message' => 'Login credentials are invalid.',

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

        // dd($request->token);
		// $this->validate($request, [
		// 	'token' => 'required'
		// ]);

		$user = Auth('api')->authenticate('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcLzcxM2lkZW50aXR5XC9wdWJsaWNcL2FwaVwvbG9naW4iLCJpYXQiOjE2NDMyODY2MTUsImV4cCI6MTY0MzI5MDIxNSwibmJmIjoxNjQzMjg2NjE1LCJqdGkiOiJaNjFVcnl4dVZmejVtTG9sIiwic3ViIjoyMywicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.KyFaVRGqBpxuQmakW1Z3mt-PDJKfXlX4pm7reNw9IEg');

        dd($user);

    }
}
