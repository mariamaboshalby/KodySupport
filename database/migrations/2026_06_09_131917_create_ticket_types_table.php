<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                // اسم النوع (دعم تقني، صيانة…)
            $table->string('slug')->unique();                      // slug للتعرف عليه
            $table->decimal('expected_cost', 10, 2)->nullable();   // التكلفة المتوقعة
            $table->boolean('is_active')->default(true);           // هل النوع مفعّل؟
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ربط التذاكر بنوع من الجدول الجديد (nullable للتوافق مع القديم)
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('ticket_type_id')
                  ->nullable()
                  ->after('address')
                  ->constrained('ticket_types')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn('ticket_type_id');
        });

        Schema::dropIfExists('ticket_types');
    }
};
