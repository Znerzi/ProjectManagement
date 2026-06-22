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
        Schema::create('project_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('developer_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['assigned', 'working', 'completed', 'rejected'])->default('assigned');
            $table->datetime('accepted_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['project_id', 'developer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_client_projects');
    }
};
