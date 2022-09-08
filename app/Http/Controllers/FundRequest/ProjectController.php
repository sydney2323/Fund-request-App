<?php

namespace App\Http\Controllers\FundRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redis;
use App\Models\Project;

class ProjectController extends Controller
{
     /**
        * @OA\Get(
        * path="/api/Admin/manage-project",
        * tags={"Admin"},
        * summary="fetch projects",
        *      @OA\Response(
        *          response=200,
        *          description=" list of projects",
        *          @OA\JsonContent()
        *       ),
        * )
        */
    public function index()
    {
        $data = Redis::get('projects');
        if ($data) {
            return response()->json([
                'projects' => json_decode($data)
            ],200);
        }else {
            $projects = Project::all();
            Redis::set('projects', $projects , 'EX', 60);
            return response()->json([
                'projects' => $projects
            ],200);
        }
    }
     /**
        * @OA\Post(
        * path="/api/Admin/manage-project",
        * tags={"Admin"},
        * summary="create project",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Transport", 
        *                  description="project", 
        *                  property="project"
        *              ),
        *          ),
        *    ),
        *      @OA\Response(
        *          response=200,
        *          description="project created",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="created successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *        @OA\Property(property="project", type="object",
         *              @OA\Property(
        *                  format="string", 
        *                  default="ovanmdokvnmo-vakv-vks-vka", 
        *                  description="id", 
        *                  property="id"
        
        *              ),
        *              @OA\Property(
        *                  format="string", 
        *                  default="Transport", 
        *                  description="project", 
        *                  property="project"
        
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
            'project' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }

        $project = Project::create(["project" => $request->project]);
        return response([
            'message' => 'created successfully',
            'project' => $project
        ],200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        if ($project) {
            return response(['project' => $project],200);
        }
        return response([
            'error' => 'Project not found'
        ],404);
    }
/**
        * @OA\Put(
        * path="/api/Admin/manage-project/{project_id}",
        * tags={"Admin"},
        * summary="update project",
        *     @OA\RequestBody(
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="Transport", 
        *                  description="project", 
        *                  property="project"
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
        *                  description="project", 
        *                  property="project"
        
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
            'project' => 'required'
        ]);
        if($validator->fails()){
            return response(['error' => $validator->errors()],400);
        }
        $project = Project::find($id);
        if ($project) {
            $project->update($data);
            return response(['message' => 'updated successfully','project' => $project],200);
        }
        return response([
            'error' => 'Project not found'
        ],404);
    }
 /**
        * @OA\Delete(
        * path="/api/Admin/manage-project/{project_id}",
        * tags={"Admin"},
        * summary="delete-project",
        *      @OA\Response(
        *          response=200,
        *          description=" delete project",
        *        @OA\JsonContent(
        *              type="object", 
        *              @OA\Property(
        *                  format="string", 
        *                  default="project deleted successfully", 
        *                  description="message", 
        *                  property="message"
        
        *              ),
        *       ),
        *    ),
        *      @OA\Response(
        *          response=404,
        *          description="project not found",
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
        *                  default="project not found", 
        *                  description="error", 
        *                  property="error"
        
        *              ),        
        *       ),
        *       ),
        * )
        */
    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project) {
            $project->delete();
            return response(['message' => 'Project deleted successfully'],200);
        }
        return response([
            'error' => 'user not found'
        ],404);
    }  
}
