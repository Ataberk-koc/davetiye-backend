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
            $table->enum('event_type', ['düğün', 'nişan', 'kına', 'söz', 'diğer'])->default('düğün')->after('wedding_date');
            $table->string('location')->nullable()->after('event_type');
            $table->text('description')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn(['event_type', 'location', 'description']);
        });
    }
};
