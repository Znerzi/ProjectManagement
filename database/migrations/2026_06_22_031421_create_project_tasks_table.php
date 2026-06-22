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
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('developer_id')->constrained('users')->onDelete('cascade');
            $table->string('module_name'); // navbar, footer, login, oauth, admin side, about page, etc.
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'ongoing', 'completed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->date('deadline')->nullable();
            $table->integer('progress')->default(0); // 0-100 percentage
            $table->text('technical_details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};
