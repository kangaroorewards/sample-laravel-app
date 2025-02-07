<!-- resources/views/customers/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customers</h1>
    <a href="{{ route('customers.create') }}" class="btn btn-primary">Add New Customer</a>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($customers['data']))
                @foreach($customers['data'] as $customer)
                    <tr>
                        <td>{{ $customer['id'] ?? '' }}</td>
                        <td>{{ ($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? '') }}</td>
                        <td>{{ $customer['email'] ?? '' }}</td>
                        <td>
                            <a href="{{ route('customers.show', $customer['id']) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('customers.edit', $customer['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('customers.destroy', $customer['id']) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr><td colspan="4">No customers found.</td></tr>
            @endif
        </tbody>
    </table>
</div>
@endsection
