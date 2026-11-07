@extends('public.layout')

@php
    $name = is_array($manifest['name'] ?? null) ? ($manifest['name']['en'] ?? $manifest['id']) : $manifest['id'];
    $desc = is_array($manifest['description'] ?? null) ? ($manifest['description']['en'] ?? '') : '';
    $hero = $page['hero'] ?? $desc;
@endphp

@section('title', $name)
@section('description', $hero)

@section('content')
<div class="wrap prose">
    <p class="badge">{{ $manifest['group'] ?? 'Module' }} · {{ $manifest['plan_tier'] ?? '' }}</p>
    <h1>{{ $name }}</h1>
    <p class="lede">{{ $hero }}</p>
    <h2>What it does</h2>
    <ul>
        @foreach (($manifest['features'] ?? []) as $feature)
            <li style="margin:.35rem 0;color:var(--muted)">{{ $feature }}</li>
        @endforeach
    </ul>
    @if (! empty($manifest['depends_on']))
        <h2>Depends on</h2>
        <p>{{ implode(', ', $manifest['depends_on']) }}</p>
    @endif
    <p style="margin-top:1.5rem"><a class="cta" href="{{ url('/demo') }}">Try in demo</a></p>
</div>
@endsection
