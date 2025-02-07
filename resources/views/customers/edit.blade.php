<!-- resources/views/customers/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Customer</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $customer['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $customer['first_name'] ?? '' }}" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $customer['last_name'] ?? '' }}" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $customer['email'] ?? '' }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Customer</button>
        <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
