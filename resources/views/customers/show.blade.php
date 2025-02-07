<!-- resources/views/customers/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer Details</h1>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $customer['id'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>First Name</th>
            <td>{{ $customer['first_name'] ?? 'N/A' }}</td>
        </tr>
        </tr>
        <tr>
            <th>Last Name</th>
            <td>{{ $customer['last_name'] ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $customer['email'] ?? 'N/A' }}</td>
        </tr>
    </table>

    <a href="{{ route('customers.edit', $customer['id']) }}" class="btn btn-warning">Edit</a>

    <form action="{{ route('customers.destroy', $customer['id']) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button onclick="return confirm('Are you sure?')" class="btn btn-danger">Delete</button>
    </form>

    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
