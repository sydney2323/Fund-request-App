<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\MonthlyBudget;
use App\Models\Category;
use App\Models\CategoryMonthlyBudget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CategoryBudgetController extends Controller
{
     /**
        * @OA\Get(
        * path="/api/finance/budget-category",
        * tags={"Finance"},
        * summary="fetch all monthly category-budget",
        *      @OA\Response(
        *          response=200,
        *          description=" list of category-budget",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index(Request $request){

        $data = Redis::get('categoryMonthlyBudgets');
        if ($data) {
            return response()->json([
                'categoryMonthlyBudgets' => json_decode($data)
            ],200);
        }else {
            $categoryMonthlyBudgets = DB::table('categories')
        ->join('category_monthly_budgets', function ($join){
            $join->on('categories.id', '=', 'category_monthly_budgets.category_id');
        })
        ->get();
            Redis::set('categoryMonthlyBudgets', $categoryMonthlyBudgets , 'EX', 60);
            return response()->json([
                'categoryMonthlyBudgets' => $categoryMonthlyBudgets
            ],200);
        }
    }

    public function show($id)
    {
        $categoryMonthlyBudget = CategoryMonthlyBudget::where('id','=',$id)->first();
        if (!$categoryMonthlyBudget) {
            return response([
                'error' => 'category Monthly Budget not found'
            ],404);
        }

        $categoryMonthlyBudget = DB::table('categories')
        ->join('category_monthly_budgets', function ($join) use($id){
            $join->on('categories.id', '=', 'category_monthly_budgets.category_id')
                 ->where('category_monthly_budgets.id', '=', $id);
        })
        ->first();
        return response([
            'category_monthly_budget' => $categoryMonthlyBudget
        ],200);
    }

     /**
        * @OA\Post(
        * path="/api/finance/budget-category",
        * tags={"Finance"},
        * summary="create category budget ",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="6535eaaa-82be-42fe-bed6-44de8a8ed7da", 
        *                  description="monthly_budget_id", 
        *                  property="monthly_budget_id"
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="6535eaaa-82be-42fe-bed6-44de8a8ed7da", 
        *                  description="category_id", 
        *                  property="category_id"
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
        *          description="budget created",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Category monthly budget created", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="category_monthly_budget", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="9a9a17d8-ff68-4600-b070-4950f7d8a788", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
        *              @OA\Property(        
        *                  format="string", 
        *                  default="9a9a17d8-ff68-4600-b070-4950f7d8a788", 
        *                  description="monthly_budget_id", 
        *                  property="monthly_budget_id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="9a9a17d8-ff68-4600-b070-4950f7d8a788", 
        *                  description="category_id", 
        *                  property="category_id"
        
        *              ),                
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="amount", 
        *                  property="amount"
        
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
           $data = $request->all();
           $validator = Validator::make($data, [
               'monthly_budget_id' => 'required',
               'category_id' => 'required|unique:category_monthly_budgets',
               'amount' => 'required'
           ]);
           if($validator->fails()){
               return response(['error' => $validator->errors()],400);
           }
           $category = Category::where('id','=',$request->category_id)->first();
           $monthlyBudget = MonthlyBudget::where('id','=',$request->monthly_budget_id)->first();
           
           if (!$category) {
               return response([
                   'error' => 'category not found'
               ],404);
           }
   
           if (!$monthlyBudget) {
               return response([
                   'error' => 'monthlyBudget not found'
               ],404);
           }
   
          $CategoryMonthlyBudget = CategoryMonthlyBudget::create($data);
   
           return response([
               'message' => 'Category monthly budget created',
               'category_monthly_budget' => $CategoryMonthlyBudget
           ],200);
       }

        /**
        * @OA\Put(
        * path="/api/finance/budget-category/{category_budget_id}",
        * tags={"Finance"},
        * summary="update category budget ",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object",       
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
        *          description="update category budget",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="updated", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="category_monthly_budget", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="9a9a17d8-ff68-4600-b070-4950f7d8a788", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
        *              @OA\Property(        
        *                  format="string", 
        *                  default="9a9a17d8-ff68-4600-b070-4950f7d8a788", 
        *                  description="monthly_budget_id", 
        *                  property="monthly_budget_id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="9a9a17d8-ff68-4600-b070-4950f7d8a788", 
        *                  description="category_id", 
        *                  property="category_id"
        
        *              ),                
        *              @OA\Property(
        *                  format="string", 
        *                  default="20000", 
        *                  description="amount", 
        *                  property="amount"
        
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
               'amount' => 'required'
           ]);
           if($validator->fails()){
               return response(['error' => $validator->errors()],400);
           }
           $categoryMonthlyBudget = CategoryMonthlyBudget::find($id);
           if ($categoryMonthlyBudget) {
               $categoryMonthlyBudget->update($data);
               return response(['message' => 'updated successfully','category_monthly_budget' => $categoryMonthlyBudget],200);
           }
           return response([
               'error' => 'category Monthly Budget not found'
           ],404);
       }
    /**
        * @OA\Delete(
        * path="/api/finance/budget-category/{category_budget_id}",
        * tags={"Finance"},
        * summary="delete category budget",
        *      @OA\Response(
        *          response=200,
        *          description=" delete category budget",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="category budget deleted successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *       ),
        *    ),
        *      @OA\Response(
        *          response=404,
        *          description="category budget not found",
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
        *                  default="category budget not found", 
        *                  description="error", 
        *                  property="error"
        
        *              ),        
        *       ),
        *       ),
        * )
        */
       public function destroy($id)
       {
           $categoryMonthlyBudget = CategoryMonthlyBudget::find($id);
           if ($categoryMonthlyBudget) {
               $categoryMonthlyBudget->delete();
               return response(['message' => 'category Monthly Budget deleted successfully'],200);
           }
           return response([
               'error' => 'category Monthly Budget not found'
           ],404);
       } 
}
