@extends('public.layout')

@section('title', 'Page not found')

@section('content')
<div class="wrap prose">
    <h1>404 — page not found</h1>
    <p>That URL is not in this GuardOps deployment.</p>
    <p><a class="cta" href="{{ url('/') }}">Home</a></p>
</div>
@endsection
