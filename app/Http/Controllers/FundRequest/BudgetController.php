<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
Use \Carbon\Carbon;
use App\Models\MonthlyBudget;
use App\Models\Category;
use App\Models\CategoryMonthlyBudget;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class BudgetController extends Controller
{
      /**
        * @OA\Get(
        * path="/api/finance/budget",
        * tags={"Finance"},
        * security={ {"bearer": {} }},
        * summary="fetch all monthly budget",
        *      @OA\Response(
        *          response=200,
        *          description=" list of budget",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index()
    {
        $data = Redis::get('monthlyBudgets');
        if ($data) {
            return response()->json([
                'monthlyBudgets' => json_decode($data)
            ],200);
        }else {
            $monthlyBudgets = MonthlyBudget::all();
            Redis::set('monthlyBudgets', $monthlyBudgets , 'EX', 60*2);
            return response()->json([
                'monthlyBudgets' => $monthlyBudgets
            ],200);
        }
        //return response(['monthlyBudgets' => $monthlyBudgets],200);
    }
     /**
        * @OA\Post(
        * path="/api/finance/budget",
        * tags={"Finance"},
        * summary="create budget",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="2", 
        *                  description="month", 
        *                  property="month"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="budget_amount", 
        *                  property="budget_amount"
        *              ),        
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="budget created",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="created successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="monthlyBudget", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="ovanmdokvnmo-vakv-vks-vka", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="2", 
        *                  description="month", 
        *                  property="month"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="Feb", 
        *                  description="month_name", 
        *                  property="month_name"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="budget_amount", 
        *                  property="budget_amount"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="0", 
        *                  description="carry_over", 
        *                  property="carry_over"
        
        *              ),         
        *              @OA\Property(
        *                  format="string", 
        *                  default="29 Feb", 
        *                  description="ending_at", 
        *                  property="ending_at"
        
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
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'month' => 'required|unique:monthly_budgets',
            'budget_amount' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }

        $month_name = Carbon::create()->month($request->month)->format('F');

        $ending_at =  Carbon::create()->month($request->month)->endOfMonth()->format('d F');

        $monthlyBudget = MonthlyBudget::create([
            'month' => $request['month'],
            'month_name' => $month_name,
            'budget_amount' => $request['budget_amount'],
            'ending_at' => $ending_at,
            'carry_over' => 0,
        ]);

        return response([
            'code' => 200,
            'message' => 'budget created',
            'monthlyBudget' => $monthlyBudget
        ]);
    }

    public function show($id)
    {
        $monthlyBudget = MonthlyBudget::find($id);

        if ($monthlyBudget) {
            return response(['monthlyBudget' => $monthlyBudget]);
        }
        return response([
            'code' => '404',
            'error' => 'monthly Budget not found'
        ]);
    }

      /**
        * @OA\Put(
        * path="/api/finance/budget/{budget_id}",
        * tags={"Finance"},
        * summary="update budget",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="budget_amount", 
        *                  property="budget_amount"
        *              ),        
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="budget update",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="update successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="monthlyBudget", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="ovanmdokvnmo-vakv-vks-vka", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="2", 
        *                  description="month", 
        *                  property="month"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="Feb", 
        *                  description="month_name", 
        *                  property="month_name"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="budget_amount", 
        *                  property="budget_amount"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="0", 
        *                  description="carry_over", 
        *                  property="carry_over"
        
        *              ),         
        *              @OA\Property(
        *                  format="string", 
        *                  default="29 Feb", 
        *                  description="ending_at", 
        *                  property="ending_at"
        
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
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function update(Request $request,$id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'budget_amount' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }
        $monthlyBudget = MonthlyBudget::find($id);
        if ($monthlyBudget) {
            $monthlyBudget->update($data);
            return response([
                'code' => 200,
                'message' => 'budget updated',
                'monthlyBudget' => $monthlyBudget
            ]);
        }
        return response([
            'code' => '404',
            'error' => 'monthly Budget not found'
        ]);
    }

     /**
        * @OA\Delete(
        * path="/api/finance/budget/{budget_id}",
        * tags={"Finance"},
        * summary="delete-budget",
        *      @OA\Response(
        *          response=200,
        *          description=" delete budget",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="budget deleted successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *       ),
        *    ),
        *      @OA\Response(
        *          response=404,
        *          description="budget not found",
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
        *                  default="budget not found", 
        *                  description="error", 
        *                  property="error"
        
        *              ),        
        *       ),
        *       ),
        * )
        */

    public function destroy($id)
    {
        $monthlyBudget = MonthlyBudget::find($id);
        if ($monthlyBudget) {
            $monthlyBudget->delete();
            return response(['message' => 'Monthly Budget deleted successfully']);
        }
        return response([
            'code' => '404',
            'error' => 'Monthly Budget not found'
        ]);
    } 


}

// $data = \DB::table('tblProducts')
// ->select( 'tblProducts.id', 'tblProducts.product_title', 'tblProducts.deleted' )
// ->where('tblProducts.deleted',0)
// ->get();
// foreach($data as $product)
// {
//     $product->classes_per_product[] = \DB::table('tblClassPerProduct')->where("product_id", $product->id)->get();
// }
// return $data;