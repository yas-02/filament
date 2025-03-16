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
        Schema::table('talks', function (Blueprint $table) {
            $table->string('length')->default(\App\Enums\TalkLength::NORMAL);
            $table->string('status')->default(\App\Enums\TalkStatus::SUBMITTED);
            $table->boolean('new_talk')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talks', function (Blueprint $table) {
            //
        });
    }
};
