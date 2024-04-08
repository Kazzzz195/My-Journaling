<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('diaries', function (Blueprint $table) {
        // カラムを追加。デフォルト値は 0
        $table->integer('send_type')->default(0);
    });
}

public function down()
{
    Schema::table('diaries', function (Blueprint $table) {
        // カラムの削除
        $table->dropColumn('send_type');
    });
}

};
