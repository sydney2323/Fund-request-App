<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\CrudResource;
use Validator;

class CrudController extends Controller
{
   /**
        * @OA\Get(
        * path="/api/Admin/manage-users",
        * tags={"Admin"},
        * summary="fetch-users",
        *      @OA\Response(
        *          response=200,
        *          description=" list of users",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index()
    {
        $users = User::all();
        return response(['users' => CrudResource::collection($users)]);
    }
        /**
        * @OA\Post(
        * path="/api/Admin/manage-users",
        * tags={"Admin"},
        * summary="create user",
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
        *                  default="user created successfully", 
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


    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'full_name' => 'required',
            'phone_no' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }
        $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        $users = User::create($data);
        return response([
            'code' => 200,
            'message' => 'user created successfully',
            'user' => new CrudResource($users)
        ]);
    }
   /**
        * @OA\Get(
        * path="/api/Admin/manage-users/{user_id}",
        * tags={"Admin"},
        * summary="fetch single user",
       *      @OA\Response(
        *          response=200,
        *          description="registered",
        *        @OA\JsonContent(
        *              type="object", 
        *        @OA\Property(property="user", type="object",
        *              @OA\Property(
        *                  format="string", 
        *                  default="49erjemfd-fdfnndfne-ennen", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
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
        * )
        */
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response(['user' => new CrudResource($user)]);
        }
        return response([
            'code' => '404',
            'error' => 'user not found'
        ]);
    }
/**
        * @OA\Put(
        * path="/api/Admin/manage-users/{user_id}",
        * tags={"Admin"},
        * summary="update user",
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
        *          description="updated",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="user updated successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="user", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="kdmvksdm-4324kn-fdv", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
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

    public function update(Request $request,$id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'full_name' => 'required',
            'phone_no' => 'required',
            'email' => 'required',
            'password' => 'required',
            'role' => 'required',
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }
        $user = User::find($id);
        if ($user) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
            $user->update($data);
            return response(['user' => new CrudResource($user), 'message' => 'user updated successfully']);
        }
        return response([
            'code' => '404',
            'error' => 'user not found'
        ]);
    }
   /**
        * @OA\Delete(
        * path="/api/Admin/manage-users/{user_id}",
        * tags={"Admin"},
        * summary="delete-users",
        *      @OA\Response(
        *          response=200,
        *          description=" delete user",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="user deleted successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *       ),
        *    ),
        *      @OA\Response(
        *          response=404,
        *          description="user not found",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="404", 
        *                  description="code", 
        *                  property="code"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="user not found", 
        *                  description="error", 
        *                  property="error"
        
        *              ),        
        *       ),
        *       ),
        * )
        */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response(['message' => 'user deleted successfully']);
        }
        return response([
            'code' => '404',
            'error' => 'user not found'
        ]);
    }  
}
