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

use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

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
    
            $user = Auth::user();
            $this->scope = $user->role;

            $token = $user->createToken($user->email.'-'.now(), [$this->scope]);
    
            return response()->json([
                'token' => $token->accessToken,
                'status' => 'successfully'
            ]);
        }else {
            return response()->json([
                'message' => 'Invalid email or password'
            ]);
        }
    }


   /**
        * @OA\Post(
        * path="/api/Admin/register",
        * operationId="authRegistered",
        * tags={"Auth"},
        * summary="user register",
        *     @OA\RequestBody(
        *         @OA\JsonContent(),
        *         @OA\MediaType(
        *            mediaType="multipart/form-data",
        *            @OA\Schema(
        *               type="object",
        *               required={"full_name", "phone_no","email", "password", "role"},
        *               @OA\Property(property="full_name", type="full_name"),
        *               @OA\Property(property="phone_no", type="phone_no"),
        *               @OA\Property(property="email", type="email"),
        *               @OA\Property(property="password", type="password"),
        *               @OA\Property(property="role", type="role")
        *            ),
        *        ),
        *    ),
        *      @OA\Response(
        *          response=201,
        *          description="Registered Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=200,
        *          description="Registered Successfully",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=422,
        *          description="Unprocessable Entity",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        *      @OA\Response(response=400, description="Bad request"),
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

        $user = User::create([
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'password' =>bcrypt($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response([ 'message' => 'created successful.',' token' => $token,]);
    }

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
                'error' => $validator->errors()->all()
            ]);
        }

        $password = Str::random(6);

        $full_name = $request->full_name;

        $text = 'Hi '.$full_name.' use your email to login in iPF-fund-request App your password is '.$password.'';
        Mail::raw($text, function ($message){
            $message->to(request()->email);
        });

        $user = User::create([
            'full_name' => $request->full_name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'password' =>bcrypt($password),
            'role' => $request->role,
        ]);

       

        return response([ 'message' => 'Invited successful.','user' => $user,]);
    }

    public function changingPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()->all()
            ]);
        }

        
        $user = User::where('email','=',$request->email)->first();
       
        if ($user && Hash::check($request->current_password, $user->password)) {

            $user->update([
                'password' => $request->new_password
           ]);

            return response([ 'message' => 'pasoword updated successful.','user' => $user,]);

        }else {

            return response([ 'message' => 'wrong email or password']);
        }
    }

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()->all()
            ]);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token]
        );

        return response([ 'token' => $token]);

    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 404,
                'error' => $validator->errors()->all()
            ]);
        }

       $check = DB::table('password_resets')->where('token','=',$request->token)->first();
       if ($check) {

        $newPassword = Str::random(6);
        User::where('email','=',$check->email)->update([
            'password' => $newPassword
        ]);
        
        $email = $check->email;
        $text = 'Hi!, your new password is '.$newPassword.'';
        Mail::raw($text, function ($message) use($email){
            $message->to($email);
        });
        return response([ 'message' => 'Check your email we have sent the new password']);
       } else {
        return response([ 'message' => 'invalid token']);
       }
       
    }

    
}
