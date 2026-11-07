<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('disk')->default('local');
            $table->string('path');
            $table->string('filename');
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('sha256', 64);
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sha256']);
        });

        Schema::create('evidence_custody_events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('media_asset_id')->constrained('media_assets')->cascadeOnDelete();
            $table->string('action');
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('hash', 64);
            $table->string('prev_hash', 64)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['media_asset_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evidence_custody_events');
        Schema::dropIfExists('media_assets');
    }
};
