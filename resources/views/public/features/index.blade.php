@extends('public.layout')

@section('title', 'Features')
@section('description', 'All GuardOps modules, generated from the product registry.')

@section('content')
<div class="wrap">
    <h1>Features</h1>
    <p class="lede">Filter by scanning groups below. Each module page is generated from the same manifest that drives plans, permissions, and navigation.</p>
    @foreach ($groups as $group => $modules)
        <h2>{{ $group }}</h2>
        <div class="grid">
            @foreach ($modules as $manifest)
                @php
                    $slug = is_array($manifest['public_page'] ?? null) ? ($manifest['public_page']['slug'] ?? $manifest['id']) : $manifest['id'];
                    $name = is_array($manifest['name'] ?? null) ? ($manifest['name']['en'] ?? $manifest['id']) : $manifest['id'];
                    $desc = is_array($manifest['description'] ?? null) ? ($manifest['description']['en'] ?? '') : '';
                @endphp
                <a class="tile" href="{{ url('/features/'.$slug) }}">
                    <span class="badge">{{ $manifest['plan_tier'] ?? 'addon' }}</span>
                    <strong style="display:block;margin-top:.4rem">{{ $name }}</strong>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($desc, 120) }}</p>
                </a>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
