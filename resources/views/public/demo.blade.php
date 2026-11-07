@extends('public.layout')

@section('title', 'Try the demo')
@section('description', 'One-click role login into GuardOps demo tenants. Password for all seeded users: password.')

@section('content')
<div class="wrap">
    <h1>Try the demo</h1>
    <p class="lede">Demo content — synthetic companies and users. Password for every account: <code>password</code>. Run <code>php artisan demo:install</code> if logins are not seeded yet.</p>

    @if ($errors->any())
        <p style="color:#b91c1c;margin:1rem 0">{{ $errors->first() }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Role</th>
                <th>Email</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user['role'] }}</td>
                    <td><code>{{ $user['email'] }}</code></td>
                    <td>
                        <form method="post" action="{{ route('public.demo.login') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ $user['email'] }}">
                            <input type="hidden" name="panel" value="{{ $user['panel'] }}">
                            <button class="cta" type="submit" style="margin-top:0;padding:.45rem .9rem;font-size:.9rem">Log in</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
