<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

//use App\OauthClients;

use Laravel\Passport\Passport;

class AuthTest extends TestCase
{
//    // use WithoutMiddleware;
//     use RefreshDatabase;

//     public function create_user_with_admin_role(){
//         $user =  User::create([
//             'full_name' => fake()->name(),
//             'email' => fake()->safeEmail(),
//             'phone_no' => 0740100005,
//             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//             'role' => 'admin',
//         ]);

//         return $user;
//     }

//     //=========================== LOGIN =========================================
   
//     public function test_required_fields_for_login()
//     {
//         $user = $this->create_user_with_admin_role();

//         $loginData = [];

//         $this->postJson('api/Admin/login', $loginData)
//             ->assertStatus(200)
//             ->assertJson([
//                 "code" => 404,
//                 "error" => [
//                     "email" => ["The email field is required."],
//                     "password" => ["The password field is required."]
//                 ]
//                 ]);
//     }

//     public function test_required_fields_for_login_email_is_given()
//     {
//         $user = $this->create_user_with_admin_role();

//         $loginData = [
//             "email" => $user->email
//         ];

//         $this->postJson('api/Admin/login', $loginData)
//             ->assertStatus(200)
//             ->assertJson([
//                 "code" => 404,
//                 "error" => [
//                     "password" => ["The password field is required."]
//                 ]
//                 ]);
//     }

//     public function test_wrong_login_crediantials(){
       
//         $loginData = [
//             'email' =>  'chexk@gmail.com',
//             'password' =>  '12345'
//         ];

//         $this->postJson('api/Admin/login', $loginData)
//             ->assertStatus(200)
//             ->assertJson([
//                 'message' =>  'Invalid email or password',
//                 'code' =>  '403'
//             ]);
//     }

//     public function test_successful_login()
//     {
//         $user = $this->create_user_with_admin_role();

//         $loginData = [
//             'email' =>  $user->email,
//             'password' =>  'password'
//         ];

//         $this->postJson('api/Admin/login', $loginData)
//             ->assertStatus(200)
//             ->assertJsonStructure([
//                 "token",
//                 "code"
//             ]);
//     }

//     //=========================== REGISTRATION =========================================

//     public function test_required_fields_for_registration()
//     { 
//         $user = $this->create_user_with_admin_role();
//         Passport::actingAs($user, [$user->role]);

//         $registerData = [];

//         $response = $this->postJson('/api/Admin/register',$registerData)
//             ->assertStatus(200)
//             ->assertJson([
//                 "code" => 404,
//                 "error" => [
//                     "full_name" => ["The full name field is required."],
//                     "phone_no" => ["The phone no field is required."],
//                     "email" => ["The email field is required."],
//                     "password" => ["The password field is required."],
//                     "role" => ["The role field is required."]
//                 ]
//             ]);
//     }

//     public function test_required_fields_for_registration_full_name_and_phone_no_is_given()
//     { 
//         $user = $this->create_user_with_admin_role();
//         Passport::actingAs($user, [$user->role]);

//         $registerData = [
//             "full_name" => $user->full_name,
//             "phone_no" => $user->phone_no
//         ];

//         $response = $this->postJson('/api/Admin/register',$registerData)
//             ->assertStatus(200)
//             ->assertJson([
//                 "code" => 404,
//                 "error" => [
//                     "email" => ["The email field is required."],
//                     "password" => ["The password field is required."],
//                     "role" => ["The role field is required."]
//                 ]
//             ]);
//     }

//     public function test_successful_registration()
//     { 
//         $user = $this->create_user_with_admin_role();
//         Passport::actingAs($user, [$user->role]);

//         $registerData = [
//             "full_name" => 'dummy',
//             "phone_no" => '123456789',
//             "email" => 'dummy@gmail.com',
//             "password" => 'password',
//             "role" => 'staff',
//         ];

//         $response = $this->postJson('/api/Admin/register',$registerData)
//             ->assertStatus(200)
//             ->assertJsonStructure([
//                 "token",
//                 "message",
//             ]);
//     }

// //=========================== INVITATION =========================================

//         public function test_required_fields_for_invitation()
//     { 
//         $user = $this->create_user_with_admin_role();
//         Passport::actingAs($user, [$user->role]);

//         $invitationData = [];

//         $response = $this->postJson('/api/Admin/invitation',$invitationData)
//             ->assertStatus(200)
//             ->assertJson([
//                 "code" => 404,
//                 "error" => [
//                     "full_name" => ["The full name field is required."],
//                     "phone_no" => ["The phone no field is required."],
//                     "email" => ["The email field is required."],
//                     "role" => ["The role field is required."]
//                 ]
//             ]);
//     }

//     public function test_required_fields_for_invitation_full_name_and_phone_no_is_given()
//     { 
//         $user = $this->create_user_with_admin_role();
//         Passport::actingAs($user, [$user->role]);

//         $invitationData = [
//             "full_name" => $user->full_name,
//             "phone_no" => $user->phone_no
//         ];

//         $response = $this->postJson('/api/Admin/invitation',$invitationData)
//             ->assertStatus(200)
//             ->assertJson([
//                 "code" => 404,
//                 "error" => [
//                     "email" => ["The email field is required."],
//                     "role" => ["The role field is required."]
//                 ]
//             ]);
//     }

//     public function test_successful_invitation()
//     { 
//        // \Artisan::call('passport:install');
//         $user = $this->create_user_with_admin_role();
//         Passport::actingAs($user, [$user->role]);

//         $invitationData = [
//             "full_name" => 'dummy',
//             "phone_no" => '123456789',
//             "email" => 'dummy@gmail.com',
//             "role" => 'staff'
//         ];

//         $response = $this->postJson('/api/Admin/invitation',$invitationData)
//             ->assertStatus(200)
//             ->assertJsonStructure([
//                 "message",
//                 "user"
//             ]);
//     }

// //=========================== activities =========================================
// public function test_fetch_activity()
// { 
//    // \Artisan::call('passport:install');
//     $user = $this->create_user_with_admin_role();
//     Passport::actingAs($user, [$user->role]);

//     $response = $this->getJson('/api/Admin/activity')
//         ->assertStatus(200)
//         ->assertJsonStructure([
//            "logs"
//         ]);
// }

// //=========================== changing-password =========================================

// public function test_required_fields_for_changing_password()
// { 
//     $changingPasswordData = [];

//     $response = $this->postJson('/api/changing-password',$changingPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             "code" => 404,
//             "error" => [
//                 "email" => ["The email field is required."],
//                 "current_password" => ["The current password field is required."],
//                 "new_password" => ["The new password field is required."]
//             ]
//         ]);
// }

// public function test_required_fields_for_changing_password_email_and_current_passsword_is_given()
// { 
//    // \Artisan::call('passport:install');
//     $user = $this->create_user_with_admin_role();
//    // Passport::actingAs($user, [$user->role]);

//     $changingPasswordData = [
//         "email" => $user->full_name,
//         "current_password" => $user->phone_no
//     ];

//     $response = $this->postJson('/api/changing-password',$changingPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             "code" => 404,
//             "error" => [
//                 "new_password" => ["The new password field is required."]
//             ]
//         ]);
// }

// public function test_changing_password_for_invalid_email_and_current_password()
// { 
//    // \Artisan::call('passport:install');
//    // $user = $this->create_user_with_admin_role();
//    // Passport::actingAs($user, [$user->role]);

//     $changingPasswordData = [
//         "email" => 'check@gmail.com',
//         "current_password" => '12345',
//         "new_password" => 'password2'
//     ];

//     $response = $this->postJson('/api/changing-password',$changingPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             'message' => 'wrong email or current password'
//         ]);
// }

// public function test_successful_changing_password()
// { 
//     $user = $this->create_user_with_admin_role();
//     //Passport::actingAs($user, [$user->role]);

//     $changingPasswordData = [
//         "email" => $user->email,
//         "current_password" => 'password',
//         "new_password" => 'password2'
//     ];
   
//     $response = $this->postJson('/api/changing-password',$changingPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             'message' => 'password updated successful',
//             'code' => '200'
//         ]);
// }
// //=========================== forgot-password =========================================

// public function test_required_fields_for_forgot_password()
// { 
//    // \Artisan::call('passport:install');
//     //$user = $this->create_user_with_admin_role();
//     //Passport::actingAs($user, [$user->role]);

//     $forgotPasswordData = [];

//     $response = $this->postJson('/api/forgot-password',$forgotPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             "code" => 404,
//             "error" => [
//                 "email" => ["The email field is required."]
//             ]
//         ]);
// }

// public function test_successful_forgot_password()
// { 
//     $user = $this->create_user_with_admin_role();
//     //Passport::actingAs($user, [$user->role]);

//     $forgotPasswordData = [
//         "email" => $user->email
//     ];
   
//     $response = $this->postJson('/api/forgot-password',$forgotPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             "message" => "token has been sent to your email"
//         ]);
// }

// //=========================== reset-password =========================================


// public function test_required_fields_for_reset_password()
// { 
//    // \Artisan::call('passport:install');
//     //$user = $this->create_user_with_admin_role();
//     //Passport::actingAs($user, [$user->role]);

//     $resetPasswordData = [];

//     $response = $this->postJson('/api/reset-password',$resetPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             "code" => 404,
//             "error" => [
//                 "token" => ["The token field is required."],
//                 "new_password" => ["The new password field is required."]
//             ]
//         ]);
// }

// public function test_reset_password_for_invalid_token()
// { 
//    // \Artisan::call('passport:install');
//    // $user = $this->create_user_with_admin_role();
//    // Passport::actingAs($user, [$user->role]);

//     $resetPasswordData = [
//         "new_password" => '1234',
//         "token" => 'klccjklacmcjpkcpKCPKpack'
//     ];

//     $response = $this->postJson('/api/reset-password',$resetPasswordData)
//         ->assertStatus(200)
//         ->assertJson([
//             "message" => "invalid token"
//         ]);
// }

}

