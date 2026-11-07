<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('country_code', 2)->default('BD');
            $table->string('locale', 10)->default('en');
            $table->string('timezone')->default('UTC');
            $table->string('currency', 3)->default('USD');
            $table->unsignedTinyInteger('week_starts_on')->default(1);
            $table->json('branding')->nullable();
            $table->string('custom_domain')->nullable()->unique();
            $table->string('labour_preset')->nullable();
            $table->unsignedBigInteger('ai_budget_cents')->default(0);
            $table->unsignedInteger('storage_quota_mb')->default(5120);
            $table->boolean('is_demo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('data_retention')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modules', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('group');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->json('depends_on')->nullable();
            $table->json('conflicts_with')->nullable();
            $table->json('permissions')->nullable();
            $table->string('plan_tier')->default('starter');
            $table->unsignedInteger('price_cents')->default(0);
            $table->boolean('is_core')->default(false);
            $table->json('manifest')->nullable();
            $table->json('public_page')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tenant_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->boolean('enabled')->default(true);
            $table->timestamp('enabled_at')->nullable();
            $table->string('source')->default('manual');
            $table->timestamps();
            $table->unique(['tenant_id', 'module_id']);
            $table->index(['tenant_id', 'enabled']);
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->nullOnDelete();
            $table->string('employee_id')->nullable()->after('email');
            $table->string('pin_hash')->nullable();
            $table->string('locale', 10)->nullable();
            $table->boolean('is_platform_admin')->default(false);
            $table->index(['tenant_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropColumn(['employee_id', 'pin_hash', 'locale', 'is_platform_admin']);
        });
        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('tenants');
    }
};
