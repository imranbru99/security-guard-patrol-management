@extends('public.layout')

@section('title', 'Document verification')
@section('description', 'Verify a GuardOps PDF document hash and authenticity code.')

@section('content')
<div class="wrap prose">
    <h1>Document verification</h1>
    <p>Code: <code>{{ $code }}</code></p>
    @if ($valid)
        <p>Format looks valid. Full hash lookup is available once the document was issued through Unicode-PDF with a verification QR.</p>
    @else
        <p>This code is too short to be a GuardOps verification token.</p>
    @endif
</div>
@endsection
