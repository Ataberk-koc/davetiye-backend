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
        Schema::create('borders', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Kenarlık adı (Örn: Çiçekli, Altın Varaklı vb.)
        $table->string('image_path'); // Resim yolu
            $table->timestamps();
        });
        Schema::table('invitations', function (Blueprint $table) {
        $table->foreignId('border_id')->nullable()->constrained('borders')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borders');
    }
    
};
