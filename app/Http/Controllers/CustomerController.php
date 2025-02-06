<?php
// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use App\Services\KangarooService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $kangaroo;

    public function __construct(KangarooService $kangaroo)
    {
        $this->kangaroo = $kangaroo;
    }

    // List all customers
    public function index()
    {
        $customers = $this->kangaroo->getCustomers();
        return view('customers.index', compact('customers'));
    }

    // Show form for creating a customer
    public function create()
    {
        return view('customers.create');
    }

    // Store a new customer
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            // add additional fields as needed
        ]);
        $result = $this->kangaroo->createCustomer($data);
        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    // Show a single customer
    public function show($id)
    {
        $customer = $this->kangaroo->getCustomer($id);
        return view('customers.show', compact('customer'));
    }

    // Show form for editing a customer
    public function edit($id)
    {
        $customer = $this->kangaroo->getCustomer($id);
        return view('customers.edit', compact('customer'));
    }

    // Update a customer
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            // add additional fields as needed
        ]);
        $result = $this->kangaroo->updateCustomer($id, $data);
        return redirect()->route('customers.show', $id)->with('success', 'Customer updated successfully.');
    }

    // Delete a customer
    public function destroy($id)
    {
        $result = $this->kangaroo->deleteCustomer($id);
        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
