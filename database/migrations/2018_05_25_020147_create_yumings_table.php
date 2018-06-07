<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYumingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yumings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->common('域名');
            $table->integer('team')->nullable()->common('小组');
            $table->integer('status')->nullable()->common('使用状态. 0未使用 1使用');
            $table->integer('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yumings');
    }
}
