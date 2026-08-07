@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-6">
    <div class="w-full max-w-sm">
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-8">Admin Login</h1>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300">
                Remember me
            </label>
            <button type="submit"
                class="w-full rounded-full bg-red-600 text-white px-6 py-3 text-sm font-semibold hover:bg-red-700 transition-colors">
                Log In
            </button>
        </form>
    </div>
</div>
@endsection