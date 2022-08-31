<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use Validator;

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
        $requests = UserRequest::all();
        return response(['requests' => $requests]);
    }

    public function show($id)
    {
        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            return response(['request' => $userRequest]);
        }
        return response([
            'code' => '404',
            'error' => 'request not found'
        ]);
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
            return response(['error' => $validator->errors()]);
        }

        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            $userRequest->update([
                'status' => true, 
                'is_receipt_required' => true,
                'reject_reason' => null
            ]);
            return response([
                'code' => 200,
                'message' => 'request accepted'
            ]);
        }
        return response([
            'code' => '404',
            'error' => 'request not found'
        ]);
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
            return response(['error' => $validator->errors()]);
        }

        $userRequest = UserRequest::where('id','=',$id)->first();
        if ($userRequest) {
            $userRequest->update([
                'status' => false, 
                'reject_reason' => $request->reason
            ]);
            return response([
                'code' => 200,
                'message' => 'request rejected'
            ]);
        }
        return response([
            'code' => '404',
            'error' => 'request not found'
        ]);
    }

    public function showRequest($id){
        $userRequest = UserRequest::findOrFail($id);
        return view('finance.view-request',compact('userRequest'));
    }

    public function createMonthlyUsedBudget($request_id){

       $userRequest = UserRequest::where('id','=',$request_id)->get();  
       $category = Category::where('category','=',$userRequest[0]->category)->get();
       $categoryMonthlyBudget = CategoryMonthlyBudget::where('id','=',$category[0]->id)->get(); 
       $data = [
        'month_name' => $userRequest[0]->month_name,
        'staff_email' => $userRequest[0]->staff_email,
        'category_name' => $userRequest[0]->category,
        'category_id' => $categoryMonthlyBudget[0]->category_id,
        'category_user_amount' => $userRequest[0]->amount,
        'project_name' => $userRequest[0]->project,
       ];

       $save = MonthlyUsedBudget::create($data);   
       return $save;

    }

}
