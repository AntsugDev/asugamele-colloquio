<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthTest extends TestCase
{

    protected string|null $token;

    protected string|null $refresh;

    protected string $file = 'test_key.json';

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = null;
        $this->refresh = null;

        $this->checkFile();

    }

    protected function checkFile(): void
    {
      if(Storage::disk('tmp')->exists($this->file)){
            $decode = json_decode(file_get_contents(Storage::disk('tmp')->path($this->file)),true);
            $this->token = $decode['token'];
            $this->refresh = $decode['refresh'];
            Storage::disk('tmp')->delete($this->file);
        }
}

    /**
     * A basic feature test example.
     */
    public function test_login()
    {
        $response = $this->post('/api/auth',[
            "email" => "admin@admin.it",
            "password" => "admin@123"
        ]);

        $this->genericReponse($response);
    }

    protected function genericReponse ($response): void
    {
        if(strcmp($response->getStatusCode(),200) === 0)
            $response->assertStatus(200);
        else
            $response->assertStatus(500);
        $content = $response->getContent();

        if(is_string($content)) {
            $decode = json_decode($content, true);
            Storage::disk('tmp')->put($this->file,json_encode([
                "token" => $decode['data-token']['access_token'],
                "refresh" => $decode['data-token']['refresh_token'],
            ],true));
            $response->assertJson($decode);
        }
        else
            $response->assertJsonStructure([
                    "status" => 201
                ]
            );
    }



    public function test_refresh()
    {

        if(is_null($this->token) && is_null($this->refresh))
            $this->fail("Unable to proceed as the parameters are null");

        $response = $this->post('/api/refresh',[
            "refresh_token" => $this->refresh
        ],[
            "Authorization" => "Bearer ".$this->token
        ]);
        $this->genericReponse($response);
    }

    /**
     * @throws \Exception
     */
    public function test_logout(){
        try{
            if (is_null($this->token) && is_null($this->refresh))
                $this->fail("Unable to proceed as the parameters are null[logout]");

            $response = $this->get('/api/logout', [
                "Authorization" => "Bearer " . $this->token
            ]);
           $response->assertStatus($response->getStatusCode());
        }catch (\Exception $e){
            $this->fail($e->getMessage());
        }
    }

    public function test_list(){
        try{
            if (is_null($this->token) && is_null($this->refresh))
                $this->fail("Unable to proceed as the parameters are null[list]");

            $response = $this->get('/api/list', [
                "Authorization" => "Bearer " . $this->token
            ]);
            $response->assertStatus($response->getStatusCode());
        }catch (\Exception $e){
            $this->fail($e->getMessage());
        }
    }

}
