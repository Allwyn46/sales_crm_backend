<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('status')->default('new');
            $table->string('source')->nullable();
            $table->decimal('value', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('last_called_at')->nullable();
            $table->timestamp('follow_up_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'assigned_to_id']);
            $table->index('source');
            $table->index('follow_up_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
