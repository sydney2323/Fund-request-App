<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use App\Models\UserRequest;
use App\Models\MonthlyUsedBudget;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redis;

class RequestHandlerController extends Controller
{
          /**
        * @OA\Get(
        * path="/api/finance/request",
        * tags={"Finance"},
        * summary="fetch all user request",
        *      @OA\Response(
        *          response=200,
        *          description=" list of request",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index()
    {
        $data = Redis::get('requests');
        if ($data) {
            return response()->json([
                'requests' => json_decode($data)
            ],200);
        }else {
            $requests = UserRequest::all();
            Redis::set('requests', $requests , 'EX', 60);
            return response()->json([
                'requests' => $requests
            ],200);
        }
    }

    public function show($id)
    {
        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            return response(['request' => $userRequest],200);
        }
        return response([
            'error' => 'request not found'
        ],404);
    }

  /**
        * @OA\Patch(
        * path="/api/finance/request/accept/{request_id}",
        * tags={"Finance"},
        * summary="accept request",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="true", 
        *                  description="is_receipt_required", 
        *                  property="is_receipt_required"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="request accepted",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="request accepted", 
        *                  description="message", 
        *                  property="message"
        
        *              ),

        *        ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function accept(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'is_receipt_required' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }

        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            $userRequest->update([
                'status' => true, 
                'is_receipt_required' => true,
                'reject_reason' => null
            ]);
            return response([
                'message' => 'request accepted'
            ],200);
        }
        return response([
            'error' => 'request not found'
        ],404);
    }

    
  /**
        * @OA\Patch(
        * path="/api/finance/request/reject/{request_id}",
        * tags={"Finance"},
        * summary="reject request",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="nooo", 
        *                  description="reject_reason", 
        *                  property="reject_reason"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="request rejected",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="request rejected", 
        *                  description="message", 
        *                  property="message"
        
        *              ),

        *        ),
        *       ),
        *      @OA\Response(
        *          response=404,
        *          description="Fields required",
        *          @OA\JsonContent()
        *       ),
        * )
        */

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reject_reason' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }

        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            $userRequest->update([
                'status' => false, 
                'reject_reason' => $request->reason
            ]);
            return response([
                'message' => 'request rejected'
            ],200);
        }
        return response([
            'error' => 'request not found'
        ],404);
    }

}
