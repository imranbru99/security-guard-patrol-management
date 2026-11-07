@extends('public.layout')

@section('title', 'Request received')

@section('content')
<div class="wrap prose">
    <h1>Thanks — request received</h1>
    <p>We recorded your enquiry from {{ $data['email'] }}. This is an acknowledgement page; sales follow-up depends on operator configuration.</p>
    <p><a href="{{ url('/') }}">Back home</a></p>
</div>
@endsection
