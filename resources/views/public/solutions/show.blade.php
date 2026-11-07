@extends('public.layout')

@section('title', $solution['title'])
@section('description', $solution['summary'])

@section('content')
<div class="wrap prose">
    <h1>{{ $solution['title'] }}</h1>
    <p class="lede">{{ $solution['summary'] }}</p>
    <p>Preset key: <code>{{ $solution['preset'] }}</code>. Apply from /app → Modules → Presets after sign-in.</p>
    <p><a class="cta" href="{{ url('/pricing') }}">Estimate modules</a></p>
</div>
@endsection
