<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disciplinary_records', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('guard_id')->constrained('guards')->cascadeOnDelete();
            $table->string('severity')->default('warning');
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('occurred_on')->nullable();
            $table->string('outcome')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'guard_id']);
            $table->index(['tenant_id', 'severity']);
        });

        Schema::create('performance_scores', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('guard_id')->constrained('guards')->cascadeOnDelete();
            $table->string('period');
            $table->decimal('score', 5, 2);
            $table->text('notes')->nullable();
            $table->date('reviewed_on')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'guard_id']);
            $table->unique(['tenant_id', 'guard_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_scores');
        Schema::dropIfExists('disciplinary_records');
    }
};
