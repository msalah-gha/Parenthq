<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class UsersTest
 * @package Tests\Feature\Users
 */
class UsersTest extends TestCase
{
    /**
     * Test Class for users module.
     *
     * @return void
     */

    /** @test */
    public function it_can_list_users_successfully()
    {
        $response = $this->json('get', 'api/v1/users/');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data',
                    'message',
                ]);
    }

    /** @test */
    public function it_will_list_users_while_sending_specific_provider()
    {
        $response = $this->json('get', 'api/v1/users/?provider=DataProviderX');
        $response->assertStatus(200);
    }

    /** @test */
    public function it_will_throw_validation_error_while_trying_to_list_users_for_wrong_provider()
    {
        $response = $this->json('get', 'api/v1/users/?provider=DataProviderXxxx');
        $response->assertStatus(422)
                 ->assertJsonStructure([
                    'message',
                    'errors' => ['provider']
                ]);
    }

    /** @test */
    public function it_will_throw_validation_error_while_trying_to_list_users_using_invalid_min_balance()
    {
        $response = $this->json('get', 'api/v1/users/?balanceMin=xxxx');
        $response->assertStatus(422)
                 ->assertJsonStructure([
                    'message',
                    'errors' => ['balanceMin']
            ]);
    }

    /** @test */
    public function it_will_throw_validation_error_while_trying_to_list_users_using_invalid_max_balance()
    {
        $response = $this->json('get', 'api/v1/users/?balanceMax=xxxx');
        $response->assertStatus(422)
                 ->assertJsonStructure([
                    'message',
                    'errors' => ['balanceMax']
            ]);
    }

    /** @test */
    public function it_will_get_no_data_while_trying_to_list_users_using_invalid_parameters_data()
    {
        $response = $this->json('get', 'api/v1/users/?provider=DataProviderX&statusCode=authorised&currency=AED&balanceMin=200.5&balanceMax=200.5');
        $response->assertStatus(422)
                 ->assertJsonStructure([
                    'success',
                    'message'
            ]);
    }
}
