@extends('public.layout')

@section('title', $page['title'])
@section('description', \Illuminate\Support\Str::limit($page['body'], 155))

@section('content')
<div class="wrap prose">
    <h1>{{ $page['title'] }}</h1>
    <p>{{ $page['body'] }}</p>
    @if ($key === 'quote')
        <form method="post" action="{{ route('public.quote.store') }}" style="margin-top:1.5rem;max-width:28rem">
            @csrf
            <p><label>Name<br><input name="name" required maxlength="120" style="width:100%;padding:.5rem;margin-top:.25rem"></label></p>
            <p style="margin-top:.75rem"><label>Email<br><input type="email" name="email" required style="width:100%;padding:.5rem;margin-top:.25rem"></label></p>
            <p style="margin-top:.75rem"><label>Company<br><input name="company" style="width:100%;padding:.5rem;margin-top:.25rem"></label></p>
            <p style="margin-top:.75rem"><label>Message<br><textarea name="message" rows="4" style="width:100%;padding:.5rem;margin-top:.25rem"></textarea></label></p>
            <button class="cta" type="submit">Send request</button>
        </form>
    @endif
</div>
@endsection
