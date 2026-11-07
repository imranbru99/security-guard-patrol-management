<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('period')->default('daily');
            $table->date('snapshot_on');
            $table->json('metrics');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'snapshot_on']);
            $table->unique(['tenant_id', 'period', 'snapshot_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_snapshots');
    }
};
