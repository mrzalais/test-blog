<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountToUser extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('count');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            //
        });
    }
}
