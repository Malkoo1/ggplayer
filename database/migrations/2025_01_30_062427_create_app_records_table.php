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
        Schema::create('app_records', function (Blueprint $table) {
            $table->id();
            $table->text('app_id')->unique();
            $table->enum('os', ['android', 'ios']);
            $table->enum('status', ['enable', 'disable'])->default('disable');
            $table->text('assign_url')->nullable();
            $table->foreignId('reseller_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('expiry_date')->nullable();
            $table->string('licence_pkg', 255)->nullable();
            $table->datetime('licence_expire')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_records');
    }
};
