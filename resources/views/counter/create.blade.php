@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Create Counter</h1>

    <form action="{{ route('counter.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium">Name</label>
            <input type="text" name="name" id="name" required class="border rounded px-3 py-2 w-full">
        </div>

        <div>
            <label for="number" class="block text-sm font-medium">Number</label>
            <input type="number" name="number" id="number" required class="border rounded px-3 py-2 w-full">
        </div>

        <div>
            <label class="inline-flex items-center">
                <input type="checkbox" name="deleted" class="form-checkbox">
                <span class="ml-2">Mark as Deleted</span>
            </label>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Save Counter
        </button>
    </form>
</div>
@endsection
