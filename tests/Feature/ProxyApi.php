<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;


/**
 * @method factory(string $class)
 */
class ProxyApi extends TestCase
{
    /**
     * A basic unit test example.
     * @throws \Exception
     */
    public function test_user (){
        try{
            $password = fake()->password;
            $email = fake()->email;
            User::factory()->create([
                'name' => fake()->name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);
            $response = $this->post('/api/auth', [
                "email" => $email,
                "password" => $password
            ]);
            $this->assertDatabaseHas('users', [
                'email' => $email,
            ]);

        }catch (\Exception $e){
          throw new \Exception($e->getMessage());
        }
    }
}
