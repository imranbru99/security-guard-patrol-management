<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_items', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->foreignId('assigned_guard_id')->nullable()->constrained('guards')->nullOnDelete();
            $table->foreignId('post_order_id')->nullable()->constrained('post_orders')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('checklist')->nullable();
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'due_at']);
        });

        Schema::create('task_completions', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('task_item_id')->constrained('task_items')->cascadeOnDelete();
            $table->foreignId('completed_by_guard_id')->nullable()->constrained('guards')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('proof_path')->nullable();
            $table->json('checklist_answers')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'task_item_id']);
        });

        Schema::create('task_post_order_acks', function (Blueprint $table): void {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('post_order_id')->constrained('post_orders')->cascadeOnDelete();
            $table->foreignId('task_item_id')->nullable()->constrained('task_items')->nullOnDelete();
            $table->foreignId('guard_id')->constrained('guards')->cascadeOnDelete();
            $table->timestamp('acknowledged_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'post_order_id', 'guard_id', 'task_item_id'], 'task_po_acks_unique');
            $table->index(['tenant_id', 'guard_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_post_order_acks');
        Schema::dropIfExists('task_completions');
        Schema::dropIfExists('task_items');
    }
};
