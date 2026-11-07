<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checkpoints', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('site_zone_id')->nullable()->constrained('site_zones')->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('verification_type', 16); // qr|nfc|gps|ble
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('radius_meters')->default(50);
            $table->string('nfc_uid')->nullable();
            $table->string('ble_uuid')->nullable();
            $table->string('qr_payload')->nullable();
            $table->boolean('requires_photo')->default(false);
            $table->boolean('requires_notes')->default(false);
            $table->text('hazard_prompt')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'site_id']);
            $table->index(['tenant_id', 'verification_type']);
        });

        Schema::create('checkpoint_tags', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('checkpoint_id')->constrained('checkpoints')->cascadeOnDelete();
            $table->string('tag_value');
            $table->string('status', 32)->default('active'); // active|damaged|missing
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'checkpoint_id']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkpoint_tags');
        Schema::dropIfExists('checkpoints');
    }
};
