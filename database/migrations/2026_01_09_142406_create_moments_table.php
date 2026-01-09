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
        Schema::create('moments', function (Blueprint $table) {
            $table->id();
            // Hangi davetiyeye ait olduğunu tutuyoruz
        $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
        $table->string('image_path'); // Resmin sunucudaki yeri
        $table->text('caption')->nullable(); // Fotoğrafın altına yazılan not
        $table->string('guest_name')->nullable(); // İstersek yükleyenin adı (Opsiyonel)
        $table->boolean('is_approved')->default(true); // İleride denetim yapmak istersen diye
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moments');
    }
};
