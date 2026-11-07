@extends('public.layout')

@section('title', 'Industries & solutions')
@section('description', 'Business presets for guarding companies — each maps to a module set.')

@section('content')
<div class="wrap">
    <h1>Industries & solutions</h1>
    <p class="lede">One-click business presets apply a curated module set. Everything remains editable afterwards.</p>
    <div class="grid">
        @foreach ($solutions as $slug => $solution)
            <a class="tile" href="{{ url('/solutions/'.$slug) }}">
                <strong>{{ $solution['title'] }}</strong>
                <p class="meta">{{ $solution['summary'] }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
