<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrol_routes', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->boolean('is_ordered')->default(true);
            $table->boolean('randomised')->default(false);
            $table->unsignedInteger('min_dwell_seconds')->nullable();
            $table->unsignedInteger('max_dwell_seconds')->nullable();
            $table->unsignedInteger('grace_minutes')->default(10);
            $table->unsignedInteger('gps_radius_meters')->default(75);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'site_id']);
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('route_checkpoints', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('patrol_route_id')->constrained('patrol_routes')->cascadeOnDelete();
            $table->foreignId('checkpoint_id')->constrained('checkpoints')->cascadeOnDelete();
            $table->unsignedInteger('sequence')->default(1);
            $table->unsignedInteger('expected_minutes_from_start')->nullable();
            $table->timestamps();

            $table->unique(['patrol_route_id', 'checkpoint_id']);
            $table->index(['tenant_id', 'patrol_route_id', 'sequence']);
        });

        Schema::create('patrol_schedules', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('patrol_route_id')->constrained('patrol_routes')->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('interval_minutes')->default(60);
            $table->time('window_start')->nullable();
            $table->time('window_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['tenant_id', 'patrol_route_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_schedules');
        Schema::dropIfExists('route_checkpoints');
        Schema::dropIfExists('patrol_routes');
    }
};
