@extends('public.layout')

@section('title', 'Pricing')
@section('description', 'Interactive module plan builder. Prices are estimates from the registry; operator sets commercial rates.')

@section('content')
<div class="wrap">
    <h1>Pricing & plan builder</h1>
    <p class="lede">Tick modules to see a live estimate. Core platform modules are included. Add-on prices come from each module manifest (minor units shown as whole currency for demo).</p>
    <form id="plan-builder">
        <table>
            <thead>
                <tr>
                    <th scope="col">Include</th>
                    <th scope="col">Module</th>
                    <th scope="col">Tier</th>
                    <th scope="col">Add-on</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $manifest)
                    @php
                        $name = is_array($manifest['name'] ?? null) ? ($manifest['name']['en'] ?? $manifest['id']) : $manifest['id'];
                        $price = (int) ($manifest['price'] ?? 0);
                    @endphp
                    <tr>
                        <td><input type="checkbox" name="modules[]" value="{{ $manifest['id'] }}" data-price="{{ $price }}" @checked(($manifest['plan_tier'] ?? '') === 'starter')></td>
                        <td>{{ $name }}</td>
                        <td>{{ $manifest['plan_tier'] ?? '' }}</td>
                        <td>{{ $price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p style="margin-top:1rem;font-size:1.15rem">Estimated add-on total: <strong id="estimate">0</strong> <span class="meta">(demo units)</span></p>
    </form>
    <noscript><p>Enable JavaScript for live totals, or sum the add-on column manually.</p></noscript>
</div>
<script>
    (function () {
        var form = document.getElementById('plan-builder');
        var out = document.getElementById('estimate');
        if (!form || !out) return;
        function recalc() {
            var total = 0;
            form.querySelectorAll('input[type=checkbox][data-price]').forEach(function (el) {
                if (el.checked) total += parseInt(el.getAttribute('data-price'), 10) || 0;
            });
            out.textContent = String(total);
        }
        form.addEventListener('change', recalc);
        recalc();
    })();
</script>
@endsection
