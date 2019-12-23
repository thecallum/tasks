<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->longText('description');
            $table->bigInteger('list_id')->unsigned()->index();
            $table->string('name');
            $table->unsignedInteger('order');
            $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
