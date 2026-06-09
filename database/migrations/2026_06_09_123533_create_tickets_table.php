<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                      // اسم العميل
            $table->string('company_name')->nullable();                   // اسم الشركة
            $table->string('phone', 30);                                  // التليفون
            $table->text('address')->nullable();                          // العنوان
            $table->enum('visit_type', [
                'technical_support',   // دعم تقني
                'consultation',        // استشارة
                'installation',        // تركيب
                'maintenance',         // صيانة
                'training',            // تدريب
                'other',               // أخرى
            ])->default('technical_support');
            $table->decimal('expected_cost', 10, 2)->nullable();          // التكلفة المتوقعة
            $table->text('notes')->nullable();                            // ملاحظات إضافية
            $table->enum('status', [
                'pending',    // قيد الانتظار
                'confirmed',  // تم التأكيد
                'in_progress',// جاري التنفيذ
                'completed',  // مكتمل
                'cancelled',  // ملغي
            ])->default('pending');
            $table->string('ticket_number')->unique();                    // رقم التذكرة
            $table->timestamp('scheduled_at')->nullable();                // موعد الزيارة
            $table->foreignId('assigned_to')->nullable()
                  ->constrained('users')->nullOnDelete();                 // الموظف المسؤول
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
