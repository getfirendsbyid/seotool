<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->common('域名前缀');
            $table->integer('team')->nullable()->common('所属分组');
            $table->integer('status')->nullable()->common('前缀状态 0 未使用 1已使用');
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
        Schema::dropIfExists('pres');
    }
}
