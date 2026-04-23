@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
    <div class="error-container" style="text-align: center; padding: 50px;">
        <h1 style="font-size: 72px; margin-bottom: 0;">404</h1>

        <div class="error-message">
            @if (str_contains($exception->getMessage(), 'Article'))
                <h2>The Article you're looking for has been archived or moved.</h2>
            @elseif(str_contains($exception->getMessage(), 'Category'))
                <h2>This Category no longer exists.</h2>
            @else
                <h2>Oops! The page you're looking for can't be found.</h2>
            @endif
        </div>

        <p style="margin: 20px 0;">Don't worry, you can still find what you need here:</p>

        <div class="actions">
            <a href="{{ route('categories.index') }}" class="btn btn-primary">Browse Categories</a>
            <span style="margin: 0 10px;">or</span>
            <a href="{{ url('/') }}" class="btn btn-secondary">Return Home</a>
        </div>
    </div>
@endsection
