<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class KangarooIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Http::preventStrayRequests();
        config(['services.kangaroo.application_key' => 'test-key']);
    }

    public function test_customers_require_a_kangaroo_session(): void
    {
        $this->get('/customers')->assertRedirect('/login');
        Http::assertNothingSent();
    }

    public function test_customer_list_uses_the_session_token_and_renders_api_data(): void
    {
        Http::fake([
            'api.kangaroorewards.com/customers' => Http::response(['data' => [
                ['id' => 'customer-1', 'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com'],
            ]]),
        ]);

        $this->withSession(['kangaroo_access_token' => 'test-token'])
            ->get('/customers')
            ->assertOk()
            ->assertSee('Jane Doe')
            ->assertSee('jane@example.com');

        Http::assertSent(fn ($request) => $request->method() === 'GET'
            && $request->url() === 'https://api.kangaroorewards.com/customers'
            && $request->hasHeader('Authorization', 'Bearer test-token')
            && $request->hasHeader('X-Application-Key', 'test-key'));
    }

    public function test_customer_creation_validates_and_sends_the_payload(): void
    {
        Http::fake(['api.kangaroorewards.com/customers' => Http::response(['data' => ['id' => 'customer-1']], 201)]);
        $customer = ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com'];

        $this->withSession(['kangaroo_access_token' => 'test-token'])
            ->post('/customers', $customer)
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        Http::assertSent(fn ($request) => $request->method() === 'POST' && $request->data() === $customer);
    }

    public function test_invalid_customer_data_does_not_call_the_api(): void
    {
        $this->withSession(['kangaroo_access_token' => 'test-token'])
            ->post('/customers', ['email' => 'invalid'])
            ->assertSessionHasErrors(['first_name', 'last_name', 'email']);

        Http::assertNothingSent();
    }

    public function test_login_stores_the_oauth_response_in_the_session(): void
    {
        config([
            'services.kangaroo.client_id' => 'test-client',
            'services.kangaroo.client_secret' => 'test-secret',
            'services.kangaroo.username' => 'test-user',
            'services.kangaroo.password' => 'test-password',
        ]);
        Http::fake(['api.kangaroorewards.com/oauth/token' => Http::response([
            'access_token' => 'test-token', 'refresh_token' => 'test-refresh', 'expires_in' => 3600,
        ])]);

        $this->get('/login')
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('kangaroo_access_token', 'test-token')
            ->assertSessionHas('kangaroo_refresh_token', 'test-refresh')
            ->assertSessionHas('kangaroo_expires_in', 3600);

        Http::assertSent(fn ($request) => $request->method() === 'POST'
            && $request['grant_type'] === 'password'
            && $request['client_id'] === 'test-client'
            && $request->hasHeader('X-Application-Key', 'test-key'));
    }

    public function test_oauth_callback_rejects_invalid_state(): void
    {
        $this->withSession(['_token' => 'expected-state'])
            ->get('/callback?state=invalid&code=test-code')
            ->assertForbidden();

        Http::assertNothingSent();
    }
}
