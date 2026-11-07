<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->string('name');
            $table->string('reference')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();

            $table->index(['tenant_id', 'client_id']);
            $table->index(['tenant_id', 'status']);
        });

        Schema::create('contract_rates', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->string('label');
            $table->unsignedBigInteger('amount_cents');
            $table->string('currency', 3)->default('USD');
            $table->string('rate_type')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'contract_id']);
        });

        Schema::create('slas', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->string('name');
            $table->string('target_metric');
            $table->decimal('target_value', 8, 2)->default(0);
            $table->timestamps();

            $table->index(['tenant_id', 'contract_id']);
        });

        Schema::create('sla_scores', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('sla_id')->constrained('slas')->cascadeOnDelete();
            $table->float('score');
            $table->timestamp('scored_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'sla_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sla_scores');
        Schema::dropIfExists('slas');
        Schema::dropIfExists('contract_rates');
        Schema::dropIfExists('contracts');
    }
};
