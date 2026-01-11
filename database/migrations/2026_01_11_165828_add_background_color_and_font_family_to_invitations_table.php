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
        Schema::table('invitations', function (Blueprint $table) {
     $table->string('background_color')->nullable();
        $table->string('title_font_family')->nullable();
        $table->string('signature_font_family')->nullable();
        $table->string('body_font_family')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
             $table->dropColumn([
            'background_color',
            'title_font_family',
            'signature_font_family',
            'body_font_family'
        ]);
        });
    }
};
