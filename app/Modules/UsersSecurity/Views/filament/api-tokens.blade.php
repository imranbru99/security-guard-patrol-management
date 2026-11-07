<x-filament-panels::page>
    <div class="prose dark:prose-invert max-w-none text-sm">
        <p>
            Personal access tokens are issued via Laravel Sanctum. Create tokens programmatically
            with <code>$user-&gt;createToken('name')</code> or from your mobile/API clients.
        </p>
        <p>
            Tokens are scoped to the authenticated user and inherit the tenant from
            <code>SetTenantFromUser</code>. Revoke unused tokens regularly.
        </p>
    </div>
</x-filament-panels::page>
