<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Models\UserRequest;
use Laravel\Passport\Passport;

class FundTest extends TestCase
{
    use RefreshDatabase;

    public function create_project(){
        $project =  Project::create([
            'project' => "Nexus"
        ]);
        return $project;
    }
    public function create_category(){
        
        $category =  Category::create([
            'category' => "Transport fee"
        ]);
        return $category;
    }

    public function make_staff_request(){
        $request = UserRequest::create([
            'reason' => fake()->text(),
            'month_name' => fake()->text(),
            'project_id' => fake()->randomDigit(),
            'category_id' => fake()->randomDigit(),
            'amount' => fake()->text(),
            'staff_id' => fake()->randomDigit()
        ]);
        return $request;
    }

    public function create_user_with_staff_role(){
        $user =  User::create([
            'full_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone_no' => 0740100005,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => 'staff',
        ]);
        return $user;
    }

    //==================== staff request ====================================
    public function test_required_fields_for_staff_make_request()
    {
        $user = $this->create_user_with_staff_role();
        Passport::actingAs($user, [$user->role]);
        $requestData = [];

        $this->postJson('api/fund', $requestData)
            ->assertStatus(200)
            ->assertJson([
                "error" => [
                    "reason" => ["The reason field is required."],
                    "category_id" => ["The category id field is required."],
                    "project_id" => ["The project id field is required."],
                    "amount" => ["The amount field is required."]
                ]
            ]);
    }

    public function test_required_fields_for_staff_make_request_reason_and_amount_are_given()
    {
        $user = $this->create_user_with_staff_role();
        Passport::actingAs($user, [$user->role]);

        $requestData = [
            "reason" => fake()->text(),
            "amount" => fake()->randomDigit()
        ];

        $this->postJson('api/fund', $requestData)
            ->assertStatus(200)
            ->assertJson([
                "error" => [
                    "category_id" => ["The category id field is required."],
                    "project_id" => ["The project id field is required."],
                ]
                ]);
    }

    public function test_required_fields_for_staff_make_request_category_id_and_project_id_are_given()
    {
        $user = $this->create_user_with_staff_role();
        Passport::actingAs($user, [$user->role]);

        $requestData = [
            "category_id" => fake()->randomDigit(),
            "project_id" => fake()->randomDigit()
        ];

        $this->postJson('api/fund', $requestData)
            ->assertStatus(200)
            ->assertJson([
                "error" => [
                    "reason" => ["The reason field is required."],
                    "amount" => ["The amount field is required."],
                ]
                ]);
    }

    public function test_successful_staff_make_request()
    {
        $user = $this->create_user_with_staff_role();
        Passport::actingAs($user, [$user->role]);
        
        $requestData = [
            "reason" => fake()->text(),
            "amount" => fake()->randomDigit(),
            "category_id" => fake()->randomDigit(),
            "project_id" => fake()->randomDigit(),
        ];

        $this->postJson('api/fund', $requestData)
            ->assertStatus(200);
    }

    public function test_fetch_all_request()
    { 
        $user = $this->create_user_with_staff_role();
        Passport::actingAs($user, [$user->role]);

        $response = $this->getJson('/api/fund')
            ->assertStatus(200)
            ->assertJsonStructure([
            "request"
            ]);
    }

    public function test_required_fields_for_staff_update_request()
    {
        $user = $this->create_user_with_staff_role();
        $request_id =$this->make_staff_request();
        Passport::actingAs($user, [$user->role]);
        $requestData = [];
        

        $this->putJson('api/fund/'.$request_id->id, $requestData)
            ->assertStatus(200)
            ->assertJson([
                "error" => [
                    "reason" => ["The reason field is required."],
                    "category_id" => ["The category id field is required."],
                    "project_id" => ["The project id field is required."],
                    "amount" => ["The amount field is required."]
                ]
            ]);
    }

    

    public function test_required_fields_for_staff_update_request_reason_and_amount_are_given()
    {
        $user = $this->create_user_with_staff_role();
        $request_id =$this->make_staff_request();
        Passport::actingAs($user, [$user->role]);

        $requestData = [
            "reason" => fake()->text(),
            "amount" => fake()->randomDigit()
        ];

        $this->putJson('api/fund/'.$request_id->id, $requestData)
            ->assertStatus(200)
            ->assertJson([
                "error" => [
                    "category_id" => ["The category id field is required."],
                    "project_id" => ["The project id field is required."],
                ]
                ]);
    }

    public function test_required_fields_for_staff_update_request_category_id_and_project_id_are_given()
    {
        $user = $this->create_user_with_staff_role();
        $request_id =$this->make_staff_request();
        Passport::actingAs($user, [$user->role]);

        $requestData = [
            "category_id" => fake()->randomDigit(),
            "project_id" => fake()->randomDigit()
        ];

        $this->putJson('api/fund/'.$request_id->id, $requestData)
            ->assertStatus(200)
            ->assertJson([
                "error" => [
                    "reason" => ["The reason field is required."],
                    "amount" => ["The amount field is required."],
                ]
                ]);
    }

    public function test_successful_staff_update_request()
    {
        $user = $this->create_user_with_staff_role();
        $request_id =$this->make_staff_request();
        Passport::actingAs($user, [$user->role]);
        
        $requestData = [
            "reason" => fake()->text(),
            "amount" => fake()->randomDigit(),
            "category_id" => fake()->randomDigit(),
            "project_id" => fake()->randomDigit(),
            'staff_id' => fake()->randomDigit()
        ];

        $this->putJson('api/fund/'.$request_id->id, $requestData)
            ->assertStatus(200);
    }

    public function test_staff_delete_request()
    {
        $user = $this->create_user_with_staff_role();
        $request_id =$this->make_staff_request();
        Passport::actingAs($user, [$user->role]);

        $this->deleteJson('api/fund/'.$request_id->id)
            ->assertStatus(200);
    }
}
