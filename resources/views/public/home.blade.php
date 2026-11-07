@extends('public.layout')

@section('title', 'Home')
@section('description', 'Plan shifts, prove patrols, and close the loop on every incident with GuardOps.')

@section('content')
<section class="hero">
    <div class="hero-inner">
        <p class="brand-hero">GuardOps</p>
        <h1>Plan shifts. Prove patrols. Close the loop on every incident.</h1>
        <p class="lede">Operations software for security companies — attendance, checkpoints, evidence, and client reporting in one modular platform.</p>
        <div>
            <a class="cta" href="{{ url('/demo') }}">Try the demo</a>
            <a class="cta secondary" href="{{ url('/features') }}">Browse features</a>
        </div>
    </div>
</section>
<div class="wrap">
    <h2>Built from a single module registry</h2>
    <p class="lede">Every capability is a toggleable module. Turn modules off and menus, routes, API endpoints, jobs, and portal sections disappear — without breaking what remains.</p>
    <div class="grid">
        @foreach (collect($modules)->take(8) as $manifest)
            @php
                $slug = is_array($manifest['public_page'] ?? null) ? ($manifest['public_page']['slug'] ?? null) : null;
                $name = is_array($manifest['name'] ?? null) ? ($manifest['name']['en'] ?? $manifest['id']) : $manifest['id'];
                $desc = is_array($manifest['description'] ?? null) ? ($manifest['description']['en'] ?? '') : '';
            @endphp
            @if ($slug)
                <a class="tile" href="{{ url('/features/'.$slug) }}">
                    <strong>{{ $name }}</strong>
                    <p class="meta">{{ \Illuminate\Support\Str::limit($desc, 110) }}</p>
                </a>
            @endif
        @endforeach
    </div>
</div>
@endsection
