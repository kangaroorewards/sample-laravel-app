<?php
// app/Services/KangarooService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KangarooService
{
    protected $baseUrl;
    protected $accessToken;
    protected $applicationKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.kangaroorewards.com';
        $this->accessToken  = session('kangaroo_access_token');
        $this->applicationKey = config('services.kangaroo.application_key');
    }

    // Common method to send API requests
    protected function request($method, $endpoint, $data = [])
    {
        $url = $this->baseUrl . $endpoint;
        $headers = [
            'X-Application-Key' => $this->applicationKey,
            'Authorization'     => 'Bearer ' . $this->accessToken,
        ];

        $response = Http::withHeaders($headers)->$method($url, $data);
        if ($response->failed()) {
            // Optionally handle errors, e.g. logging or token refresh
            throw new \Exception("API call to {$endpoint} failed: " . $response->body());
        }
        return $response->json();
    }

    // ----------------------
    // Customers Endpoints
    // ----------------------

    public function getCustomers()
    {
        return $this->request('get', '/customers');
    }

    public function createCustomer(array $data)
    {
        return $this->request('post', '/customers', $data);
    }

    public function getCustomer($id)
    {
        return $this->request('get', "/customers/{$id}");
    }

    public function updateCustomer($id, array $data)
    {
        return $this->request('put', "/customers/{$id}", $data);
    }

    public function deleteCustomer($id)
    {
        return $this->request('delete', "/customers/{$id}");
    }

    // Similarly add methods for transactions, offers, rewards, etc.
}
