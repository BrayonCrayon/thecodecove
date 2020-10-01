<?php


namespace Tests;


use App\Models\User;
use Laravel\Sanctum\Sanctum;

class Utility
{
    private $testInstance;
    public $user;


    public function __construct($testInstance)
    {
        $this->testInstance = $testInstance;
    }

    public function testSetup()
    {
        $this->user = User::get()->first();
        $this->user->tokens()->delete();
    }

    public function loginAdmin()
    {
        Sanctum::actingAs(
            $this->user,
            ['admin']
        );
        $this->user->createToken($this->user->name . '-token', ['admin']);
    }

    public function loginGuest()
    {
        Sanctum::actingAs(
            $this->user,
            ['guest']
        );
        $this->user->createToken($this->user->name . '-token', ['guest']);
    }

    public function getJson($uri, $data, $headers = [])
    {
        return $this->testInstance->json('GET', $uri, $data, $headers);
    }
}
