<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_slots', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('code');
            $table->string('zone')->nullable();
            $table->string('status')->default('available');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('vehicle_logs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('parking_slot_id')->nullable()->constrained('parking_slots')->nullOnDelete();
            $table->string('plate_number');
            $table->string('vehicle_type')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_id_number')->nullable();
            $table->string('purpose')->nullable();
            $table->string('direction')->default('in');
            $table->decimal('weighbridge_in_kg', 12, 2)->nullable();
            $table->decimal('weighbridge_out_kg', 12, 2)->nullable();
            $table->timestamp('entered_at')->nullable();
            $table->timestamp('exited_at')->nullable();
            $table->boolean('violation')->default(false);
            $table->string('violation_note')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'plate_number']);
            $table->index(['tenant_id', 'entered_at']);
        });

        Schema::create('gate_passes', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('vehicle_log_id')->nullable()->constrained('vehicle_logs')->nullOnDelete();
            $table->string('pass_number');
            $table->string('goods_description')->nullable();
            $table->string('requester_name')->nullable();
            $table->string('status')->default('pending');
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_to')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'pass_number']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gate_passes');
        Schema::dropIfExists('vehicle_logs');
        Schema::dropIfExists('parking_slots');
    }
};
