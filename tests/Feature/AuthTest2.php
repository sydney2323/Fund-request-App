<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

//use App\OauthClients;

use Laravel\Passport\Passport;

class AuthTest2 extends TestCase
{
   // use WithoutMiddleware;
   //use RefreshDatabase;

    public $user_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyNSIsImp0aSI6ImYxZGMzN2ExNzY2YmUwZTc1NzU3OTcyNTE5ZjE4NDIwZWNlZTJmYzYxMDkwOTExOTY1YjEyN2EwYmFjMTEwMmYyNGZhY2I0ZDI5ZGY0YjkwIiwiaWF0IjoxNjYxMjI4MjgxLjg0NDI2LCJuYmYiOjE2NjEyMjgyODEuODQ0MjY0LCJleHAiOjE2OTI3NjQyODEuODEyMzc5LCJzdWIiOiIxMDAiLCJzY29wZXMiOlsiYWRtaW4iXX0.gGb--akmD4jTwRDiEg6nKzgxNeqko56KmHYrZngyRNLWLL0OTuzh8r2dXYa9Ikv-ZAoZtlOHaaPgxKDXPgEsGegJSdvyZh9jGa6o5Ii6kc5MibelFl0ZB2ClJkr55L7X4VeCnMKSUW9Ddkj6iKygyVGuMEvLqmz0We0tHGK3PZODI4ASKEqj1bhkyrUT8esTFztSqIGzR9jkHHdeH7RQ7zmIYakodT8AMOeGG6-mlcQI_EkaXiHGpKqpubhkcTcwD-8YYh93o7vIVTIlpqCsajDHZ7S4DJsCt5qi0rG4p_kl_sYXdEExhlq8IWF9UrslevNO4A4bFKkPQEfX1bQ-l2Xi4k1T81tNaKok2zxZ6i9XFbm7aJr6vFLthIpy8n_Tv0MkLsTgoYi2Ei2UqtRAyDYHLX-lkED5fPL4ssdQTQ0ToQEOrwh_Ol5l7_p4ffUhH2NAklCnGTWZ13E2xTcOoP7QgWMNUU2LRO-WKnj3-jeXY1tRnIi729cP1EYxGRIH54ikjmrOnTvt33KBb6RFMSVAkVSS5ewO68iC6d8cjzOH7EEY5oSScYQkUjMCMWZT2mPnIgURVKde1iWWA3POvmXH0N4RCjlyPiU39hK8FpVxSm7nseczVlJsZs_b_oPkAOZf2X3EnyUo0QgH0eLKw3J6WRUAVZPB3uVtLBukD0w';
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // public function testSuccessfulLogin()
    // {
    //     \Artisan::call('passport:install');
    //     $user = User::factory()->create([
    //         'email' => 'sample@test.com',
    //         'password' => bcrypt('sample123'),
    //      ]);


    //     $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

    //     $this->json('POST', 'api/Admin/login', $loginData, ['Accept' => 'application/json'])
    //         ->assertStatus(200);

    //     $this->assertAuthenticated();
    // }
//     public function testApiLogin() {
//     $body = [
//         'username' => 'admin2022@gmail.com',
//         'password' => 'password'
//     ];
//     $this->json('POST','/api/Admin/login',$body,['Accept' => 'application/json'])
//         ->assertStatus(200);
//         //->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);
// }
    public function test_required_fields_for_registration()
    { 
       
    //    // \Artisan::call('passport:install');
    //     $user = ['email' => 'admin2022@gmail.com','password' => 'password'];
    //    // $user = factory(User::class)->create();
    //     Passport::actingAs($user);
    //     $token = $user->createToken('TestToken')->accessToken;

    $user =  User::create([
        'full_name' => fake()->name(),
        'email' => fake()->safeEmail(),
        'phone_no' => 0740100005,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'role' => 'admin',
    ]);
       Passport::actingAs($user, ['finance']);

        $role = $user->role;

        $token = $user->createToken('test-api', ['admin'])->accessToken;
       

    //    $this->user_token = $this->generateToken();
    //     $token =  $this->user_token;
        
       // $headers = [ 'Authorization' => 'Bearer $token'];
       // $headers = ['authorization' => "Bearer $token"];
        $response = $this->post('/api/Admin/register', [
            'Authorization' => "Bearer $token"
        ])->assertStatus(200)
            ->assertJson([
                "code" => 404,
                "error" => [
                    "full_name" => ["The full name field is required."],
                    "phone_no" => ["The phone no field is required."],
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                    "role" => ["The role field is required."]
                ]
            ]);

            //dd($response);
    }

    // public function test_two_required_fields_for_registration()
    // {  
    //     $userRegistrationData = [
    //         "full_name" => "juma john",
    //         "phone_no" => "0744010005",
    //         "email" => "john@gmail.com",
    //         "password" => "123",
    //         "role" => "admin"
    //     ];

    //     //$token =  $this->generateToken();
    //     //$token =  $this->user_token;
        
    //    // $headers = [ 'Authorization' => 'Bearer $token'];

    //     $this->postJson('api/Admin/register',$userRegistrationData)
    //         ->assertStatus(401)
    //         ->assertJsonStructure([
    //                         "you"
    //                     ]);
    // }

    // public function testSuccessfulRegistration()
    // {
    //     $userData = [
    //                 "full_name" => "juma john",
    //                 "phone_no" => "0744010005",
    //                 "email" => "jouuhn@gmail.com",
    //                 "password" => "123",
    //                 "role" => "admin"
    //     ];

    //     $this->json('POST', 'api/Admin/register', $userData, ['Accept' => 'application/json'])
    //         ->assertStatus(200)
    //         ->assertJsonStructure([
    //             "message",
    //             "token"
    //         ]);
    // }

    // public function generateToken() {

    //     $user =  Passport::actingAs(
    //         User::create([
    //             'full_name' => fake()->name(),
    //             'email' => fake()->safeEmail(),
    //             'phone_no' => 0740100005,
    //             'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    //             'role' => 'admin',
    //         ])
    //     );

    //     $role = $user->role;

    //     $token = $user->createToken('test-api', [$user->role])->accessToken;
    //     //$token = Auth::user()->createToken('API Token')->accessToken;
    //     $this->assertAuthenticated();

    //     return $token; 
    // }

    /**
* @group apilogintests
*/    
// public function testApiLogin() {
//     $body = [
//         'username' => 'admin2022@gmail.com',
//         'password' => 'password'
//     ];
//     $this->json('POST','/api/Admin/login',$body,['Accept' => 'application/json'])
//         ->assertStatus(200);
//         //->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);
// }
/**
 * @group apilogintests
 */
// public function testOauthLogin() {
//     $oauth_client_id = env('PASSPORT_CLIENT_ID');
//     $oauth_client = \Laravel\Passport\Client::findOrFail($oauth_client_id);
//    // $oauth_client = OauthClients::findOrFail($oauth_client_id);

//     $body = [
//         'username' => 'admin2022@gmail.com',
//         'password' => 'password',
//         'client_id' => $oauth_client_id,
//         'client_secret' => $oauth_client->secret,
//         'grant_type' => 'password',
//         'scope' => '*'
//     ];
//     dd($this->json('POST','/oauth/token',$body,['Accept' => 'application/json'])
//         ->assertStatus(200));
//        // ->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);
}


