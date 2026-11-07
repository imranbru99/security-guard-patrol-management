<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alarms', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('dispatch_job_id')->nullable()->constrained('dispatch_jobs')->nullOnDelete();
            $table->string('external_id')->nullable();
            $table->string('source')->default('webhook');
            $table->string('alarm_type')->nullable();
            $table->string('severity')->default('medium');
            $table->string('status')->default('received');
            $table->string('outcome_code')->nullable();
            $table->boolean('false_alarm')->default(false);
            $table->timestamp('received_at')->nullable();
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->unsignedInteger('sla_seconds')->nullable();
            $table->unsignedInteger('response_seconds')->nullable();
            $table->boolean('sla_breached')->default(false);
            $table->json('payload')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'site_id']);
            $table->index(['tenant_id', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarms');
    }
};
