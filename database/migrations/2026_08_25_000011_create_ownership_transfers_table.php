<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ownership_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('previous_owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('new_owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('adoption_request_id')->nullable()->constrained('adoption_requests')->nullOnDelete();
            $table->foreignId('approved_by')->constrained('users')->cascadeOnDelete();
            $table->text('transfer_reason')->nullable();
            $table->string('transfer_status')->default('completed');
            $table->timestamp('transferred_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ownership_transfers');
    }
};
