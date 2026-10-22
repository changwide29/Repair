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
Schema::create('repair_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->comment('ชื่อลูกค้า');
            $table->string('customer_email')->comment('อีเมลลูกค้า');
            $table->string('device_name')->comment('ชื่ออุปกรณ์/รุ่น');
            $table->text('problem_description')->comment('อาการเสีย');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending')->comment('สถานะงานซ่อม');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_tickets');
    }
};
