<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Validator;
use Auth;
use Spatie\Activitylog\Models\Activity;


use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    
   /**
        * @OA\Post(
        * path="/api/Admin/login",
        * tags={"Auth"},
        * summary="user login",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="example@gmail.com", 
        *                  description="email", 
        *                  property="email"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="xx", 
        *                  description="password", 
        *                  property="password"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="logged in",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="komcqwmcfoqwmfcfoqwkcmwocmqoefmweokmf", 
        *                  description="token", 
        *                  property="token"
        *              ),
        *          ),
        *       ),
        *        @OA\Response(
        *          response=403,
        *          description="wrong crediantials",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Invalid email or password", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *          ),
        *       ),
        * )
        */

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()
            ]);
        }
    
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {

            // activity()
            // ->withProperties(Auth::user())
            // ->event('login')
            // ->log('user logged in');

            $event = 'login';
            $description =  Auth::user()->full_name.' logged in.';
            \LogActivity::addToLog($event, $description);
    
            $user = Auth::user();

            $token = $user->createToken('iPF-login', [$user->role]);
    
            return response()->json([
                'token' => $token->accessToken,
                'code' => 200
            ]);
        }else {
            return response()->json([
                'message' => 'Invalid email or password',
                'code'  => 403
            ]);
        }
    }
    

    public function logout(){
        $user = Auth::user()->token();
        // activity()
        //     ->withProperties(Auth::user())
        //     ->event('logout')
        //     ->log('user logged out');

        $event = 'logout';
            $description =  Auth::user()->full_name.' logged out.';
            \LogActivity::addToLog($event, $description);

        $user->revoke();
        
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


      /**
        * @OA\Post(
        * path="/api/Admin/register",
        * tags={"Auth"},
        * summary="user register",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="example", 
        *                  description="full_name", 
        *                  property="full_name"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="example@gmail.com", 
        *                  description="email", 
        *                  property="email"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="07xxxxxxxx", 
        *                  description="phone_no", 
        *                  property="phone_no"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="****", 
        *                  description="password", 
        *                  property="password"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="staff", 
        *                  description="role", 
        *                  property="role"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="registered",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="registered successfully", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="ksnfclKFMCLkmcklaCMAKLSCMKLMCKLASCM", 
        *                  description="token", 
        *                  property="token"
        *              ),
        *          ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()
            ]);
        }

        $user =[
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'password' =>bcrypt($request->password),
            'role' => $request->role,
        ];

        $user = User::create($user);

        // activity()
        // ->withProperties($user)
        // ->event('register')
        // ->log(''.Auth::user()->full_name.' registered user');

        $event = 'register';
        $description =  Auth::user()->full_name.' registered '.$request->email;
            \LogActivity::addToLog($event, $description);

        $token = $user->createToken('iPF')->accessToken;

        return response()->json([
            'message' => 'registered successfully',
            'token' => $token,
        ]);
    }

      /**
        * @OA\Post(
        * path="/api/Admin/invitation",
        * tags={"Auth"},
        * summary="user invitation",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="example", 
        *                  description="full_name", 
        *                  property="full_name"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="example@gmail.com", 
        *                  description="email", 
        *                  property="email"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="07xxxxxxxx", 
        *                  description="phone_no", 
        *                  property="phone_no"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="staff", 
        *                  description="role", 
        *                  property="role"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="registered",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="invitated successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="users", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="example", 
        *                  description="full_name", 
        *                  property="full_name"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="example@gmail.com", 
        *                  description="email", 
        *                  property="email"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="07xxxxxxxx", 
        *                  description="phone_no", 
        *                  property="phone_no"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="staff", 
        *                  description="role", 
        *                  property="role"
        
        *              ),
        *        ),
        *        ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */


        

    public function invitation(Request $request){

        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()
            ]);
        }

        $password = Str::random(6);

        $full_name = $request->full_name;

        $text = 'Hi '.$full_name.' use your email to login in iPF-fund-request App your password is '.$password.'';
        Mail::raw($text, function ($message){
            $message->to(request()->email);
        });

        $user =[
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'password' =>bcrypt($request->password),
            'role' => $request->role,
        ];

        $user = User::create($user);

        // activity()
        // ->withProperties($user)
        // ->event('invitation')
        // ->log(''.Auth::user()->full_name.' invited user');

        $event = 'invitation';
        $description =  Auth::user()->full_name.' invitated '.$request->email;
            \LogActivity::addToLog($event, $description);

       

        return response([ 'message' => 'Invited successful.','user' => $user,]);
    }

   /**
        * @OA\Post(
        * path="/api/changing-password",
        * tags={"Auth"},
        * summary="user changing-password",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="example@gmail.com", 
        *                  description="email", 
        *                  property="email"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="123", 
        *                  description="current_password", 
        *                  property="current_password"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="123", 
        *                  description="new_password", 
        *                  property="new_password"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="password updated",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="password updated successful.", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *          ),
        *       ),
        *        @OA\Response(
        *          response=403,
        *          description="wrong crediantials",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="wrong email or current password", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *          ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */

    public function changingPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()
            ]);
        }

        
        $user = User::where('email','=',$request->email)->first();
       
        if ($user && Hash::check($request->current_password, $user->password)) {

            $user->update([
                'password' => $request->new_password
           ]);

        //    activity()
        //     ->withProperties($user)
        //     ->event('changing-password')
        //     ->log('user changed password user');
        // $event = 'changing-password';
        // $description = $request->email.' changed password';
        //     \LogActivity::addToLog($event, $description);

            return response()->json([
                'message' => 'password updated successful',
                'code' => '200'
            ]);

        }else {
            return response()->json([
                'message' => 'wrong email or current password'
            ]);
        }
    }

       /**
        * @OA\Post(
        * path="/api/forgot-password",
        * tags={"Auth"},
        * summary="user forgot-password",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="example@gmail.com", 
        *                  description="email", 
        *                  property="email"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="token sent",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="token has been sent to your email.", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *          ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()
            ]);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token]
        );

        return response([ 'message' => 'token has been sent to your email']);

    }

           /**
        * @OA\Post(
        * path="/api/reset-password",
        * tags={"Auth"},
        * summary="user reset-password",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="123", 
        *                  description="new_password", 
        *                  property="new_password"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="klSMDCLKmcklaMCLKAMCKLASCMLKMCLKAMCKLAMCKLAMC", 
        *                  description="token", 
        *                  property="token"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="token sent",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="You have successfully reset your password, Check your email we have sent the new password.", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *          ),
        *       ),
        *      @OA\Response(
        *          response=403,
        *          description="token sent",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="invalid token or email.", 
        *                  description="message", 
        *                  property="message"
        *              ),
        *          ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'new_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()
            ]);
        }

       $check = DB::table('password_resets')->where('token','=',$request->token)->first();
       if($check) {

        $newPassword = $request->newPassword;
        $user = User::where('email','=',$check->email)->update([
            'password' => $newPassword
        ]);

        // $event = 'changing-password';
        // $description = $request->email.' changed password';
        //     \LogActivity::addToLog($event, $description);

        if ($user) {
            $email = $check->email;
            $text = 'Hi!, your new password is '.$newPassword.'';
            Mail::raw($text, function ($message) use($email){
                $message->to($email);
            });
            return response([ 'message' => 'You have successfully reset your password, Check your email we have sent the new password']);
        }else {
            return response([ 'message' => 'invalid email']);
        }
           
       } else {
        return response([ 'message' => 'invalid token']);
       }
       
    }

    /**
        * @OA\Get(
        * path="/api/Admin/activity",
        * tags={"Auth"},
        * summary="activity",
        *      @OA\Response(
        *          response=200,
        *          description=" list of activities",
        *          @OA\JsonContent()
        *       ),
        * )
        */

    public function activity(){

        
         //$activities = Activity::all(); //returns the last logged activity
 
         //$activities->description;

         $logs = \LogActivity::logActivityLists();
 
         return response()->json([
             'logs' => $logs
         ]);
     }

    
}
