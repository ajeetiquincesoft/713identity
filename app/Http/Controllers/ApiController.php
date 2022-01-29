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
}
