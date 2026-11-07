<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patrol_tours', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('patrol_route_id')->constrained('patrol_routes')->cascadeOnDelete();
            $table->foreignId('guard_id')->constrained('guards')->cascadeOnDelete();
            $table->string('status', 32)->default('pending'); // pending|active|completed|cancelled
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('due_by')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'guard_id']);
            $table->index(['tenant_id', 'patrol_route_id']);
        });

        Schema::create('patrol_scans', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('patrol_tour_id')->constrained('patrol_tours')->cascadeOnDelete();
            $table->foreignId('checkpoint_id')->constrained('checkpoints')->cascadeOnDelete();
            $table->foreignId('guard_id')->constrained('guards')->cascadeOnDelete();
            $table->uuid('client_uuid');
            $table->timestamp('scanned_at');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('verification_type', 16)->nullable();
            $table->boolean('mock_location')->default(false);
            $table->string('device_id')->nullable();
            $table->text('notes')->nullable();
            $table->string('photo_path')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'client_uuid']);
            $table->index(['tenant_id', 'patrol_tour_id']);
            $table->index(['tenant_id', 'checkpoint_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patrol_scans');
        Schema::dropIfExists('patrol_tours');
    }
};
