<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Category;

class CategoryController extends Controller
{
       /**
        * @OA\Get(
        * path="/api/Admin/manage-category",
        * tags={"Admin"},
        * summary="fetch-categories",
        *      @OA\Response(
        *          response=200,
        *          description=" list of users",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index()
    {
        $categories = Category::all();
        return response(['categories' => $categories]);
    }
        /**
        * @OA\Post(
        * path="/api/Admin/manage-category",
        * tags={"Admin"},
        * summary="create category",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Transport", 
        *                  description="category", 
        *                  property="category"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="category created",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="created successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="category", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="ovanmdokvnmo-vakv-vks-vka", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="Transport", 
        *                  description="category", 
        *                  property="category"
        
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
            'category' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }

        $category = Category::create(["category" => $request->category]);
        return response([
            'code' => 200,
            'message' => 'created successfully',
            'category' => $category
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return response(['category' => $category]);
        }
        return response([
            'code' => '404',
            'error' => 'Category not found'
        ]);
    }
 /**
        * @OA\Put(
        * path="/api/Admin/manage-category/{category_id}",
        * tags={"Admin"},
        * summary="update category",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Transport", 
        *                  description="category", 
        *                  property="category"
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
        *                  default="Transport", 
        *                  description="category", 
        *                  property="category"
        
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
            'category' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()]);
        }
        $category = Category::find($id);
        if ($category) {
            $category->update($data);
            return response(['message' => 'updated successfully','category' => $category]);
        }
        return response([
            'code' => '404',
            'error' => 'category not found'
        ]);
    }
   /**
        * @OA\Delete(
        * path="/api/Admin/manage-category/{category_id}",
        * tags={"Admin"},
        * summary="delete-category",
        *      @OA\Response(
        *          response=200,
        *          description=" delete category",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="category deleted successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *       ),
        *    ),
        *      @OA\Response(
        *          response=404,
        *          description="category not found",
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
        *                  default="category not found", 
        *                  description="error", 
        *                  property="error"
        
        *              ),        
        *       ),
        *       ),
        * )
        */
    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->delete();
            return response(['message' => 'category deleted successfully']);
        }
        return response([
            'code' => '404',
            'error' => 'user not found'
        ]);
    }  
}
