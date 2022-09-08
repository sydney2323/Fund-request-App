<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
Use \Carbon\Carbon;
use App\Models\UserRequest;
use App\Models\Project;
use App\Models\Category;
use Illuminate\Support\Facades\Redis;
use App\Models\MonthlyBudget;
use Auth;

class RequestController extends Controller
{
        /**
        * @OA\Get(
        * path="/api/fund",
        * tags={"Staff"},
        * summary="fetch request",
        *      @OA\Response(
        *          response=200,
        *          description=" list of request",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index(){
        $id = Auth::user()->id;

        $data = Redis::get('requests');
        if ($data) {
            return response()->json([
                'requests' => json_decode($data)
            ],200);
        }else {
            $requests = UserRequest::where('staff_id','=',$id)->get();
            Redis::set('requests', $requests , 'EX', 60);
            return response()->json([
                'requests' => $requests
            ],200);
        }
       
    }

     /**
        * @OA\Post(
        * path="/api/fund",
        * tags={"Staff"},
        * summary="make request ",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="515f021c-d3a0-464f-af72-bb0a08140d7f", 
        *                  description="category_id", 
        *                  property="category_id"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="515f021c-d3a0-464f-af72-bb0a08140d7f", 
        *                  description="project_id", 
        *                  property="project_id"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="kawa kwa kwa wka", 
        *                  description="reason", 
        *                  property="reason"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="amount", 
        *                  property="amount"
        *              ),                        
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="request created",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Your request is sent please wait for feedback", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="request", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="", 
        *                  description="reason", 
        *                  property="reason"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="Jan", 
        *                  description="month_name", 
        *                  property="month_name"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="a2a0f2e5-f766-4267-91b2-c7ef00a05177", 
        *                  description="catrgory_id", 
        *                  property="catrgory_id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="a2a0f2e5-f766-4267-91b2-c7ef00a05177", 
        *                  description="product_id", 
        *                  property="product_id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="amount", 
        *                  property="amount"
        
        *              ), 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Jan", 
        *                  description="staff_id", 
        *                  property="staff_id"
        
        *              ), 
        *              @OA\Property(
        *                  format="string", 
        *                  default="a2a0f2e5-f766-4267-91b2-c7ef00a05177", 
        *                  description="id", 
        *                  property="id"
        
        *              ),                                        
        *              @OA\Property(
        *                  format="string", 
        *                  default="2022-08-30T06:55:08.000000Z", 
        *                  description="created_at", 
        *                  property="created_at"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="2022-08-30T06:55:08.000000Z", 
        *                  description="updated_at", 
        *                  property="updated_at"
        
        *              ),
        *        ),
        *        ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *       ),
        * )
        */

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'category_id' => 'required',
            'project_id' => 'required',
            'amount' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }

        $category = Category::where('id','=',$request->category_id)->first();
        $project = Project::where('id','=',$request->project_id)->first();
        
        if (!$category) {
            return response([
                'error' => 'category not found'
            ],404);
        }

        if (!$project) {
            return response([
                'error' => 'product not found'
            ],404);
        }

        $staff_id = Auth::user()->id;
        $created_at = Carbon::now()->toDateString();
        $month_name = Carbon::parse($created_at)->format('F');

       $request = UserRequest::create([
            'reason' => $request['reason'],
            'month_name' => $month_name,
            'project_id' => $request['project_id'],
            'category_id' => $request['category_id'],
            'amount' => $request['amount'],
            'staff_id' => $staff_id
        ]);
        return response([
            'message' => 'Your request is sent please wait for feedback',
            'request' => $request
        ],200);
    }

         /**
        * @OA\Put(
        * path="/api/fund/{request_id}",
        * tags={"Staff"},
        * summary="update request ",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="515f021c-d3a0-464f-af72-bb0a08140d7f", 
        *                  description="category_id", 
        *                  property="category_id"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="515f021c-d3a0-464f-af72-bb0a08140d7f", 
        *                  description="project_id", 
        *                  property="project_id"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="kawa kwa kwa wka", 
        *                  description="reason", 
        *                  property="reason"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="amount", 
        *                  property="amount"
        *              ),                        
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="request updated",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Your request is updated", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="request", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="", 
        *                  description="reason", 
        *                  property="reason"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="Jan", 
        *                  description="month_name", 
        *                  property="month_name"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="a2a0f2e5-f766-4267-91b2-c7ef00a05177", 
        *                  description="catrgory_id", 
        *                  property="catrgory_id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="a2a0f2e5-f766-4267-91b2-c7ef00a05177", 
        *                  description="product_id", 
        *                  property="product_id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="amount", 
        *                  property="amount"
        
        *              ), 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Jan", 
        *                  description="staff_id", 
        *                  property="staff_id"
        
        *              ), 
        *              @OA\Property(
        *                  format="string", 
        *                  default="a2a0f2e5-f766-4267-91b2-c7ef00a05177", 
        *                  description="id", 
        *                  property="id"
        
        *              ),                                        
        *              @OA\Property(
        *                  format="string", 
        *                  default="2022-08-30T06:55:08.000000Z", 
        *                  description="created_at", 
        *                  property="created_at"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="2022-08-30T06:55:08.000000Z", 
        *                  description="updated_at", 
        *                  property="updated_at"
        
        *              ),
        *        ),
        *        ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *       ),
        * )
        */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
            'category_id' => 'required',
            'project_id' => 'required',
            'amount' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }

        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            $userRequest->update([
                'reason' => $request['reason'],
                'project' => $request['project'],
                'category' => $request['category'],
                'amount' => $request['amount'],
                'status' => false, 
                'reject_reason' => null
            ]);
            return response([
                'message' => 'Your request is updated please wait for feedback'
            ],200);
        }
        return response([
            'error' => 'request not found'
        ],404);

        
    }

     /**
        * @OA\Delete(
        * path="/api/fund/{request_id}",
        * tags={"Staff"},
        * summary="delete-request",
        *      @OA\Response(
        *          response=200,
        *          description=" delete request",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Your request is deleted", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *       ),
        *    ),
        *      @OA\Response(
        *          response=404,
        *          description="request not found",
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
        *                  default="request not found", 
        *                  description="error", 
        *                  property="error"
        
        *              ),        
        *       ),
        *       ),
        * )
        */

    public function destroy($id){
        $userRequest = UserRequest::where('id',$id)->first();
        if ($userRequest && $userRequest->status == 0) {
            $userRequest->delete();
            return response([
                'message' => 'Your request is deleted.'
            ],200);
        }elseif($userRequest && $userRequest->status == 1) {
            return response([
                'message' => 'Your request is cant be deleted since its accepted.'
            ],400);
        }else {
        return response([
            'error' => 'request not found'
        ],404);
        }
    }



}
