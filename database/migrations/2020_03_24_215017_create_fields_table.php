<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('fields')) {
            Schema::create('fields', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('type', ['date', 'number', 'string', 'boolean'])->default('string');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('field_subscriber')) {
            Schema::create('field_subscriber', function (Blueprint $table) {
                $table->unsignedBigInteger('subscriber_id');
                $table->unsignedBigInteger('field_id');
                $table->string('value');
                $table->timestamps();

                $table->primary(['subscriber_id', 'field_id']);

                $table->foreign('subscriber_id')
                    ->references('id')->on('subscribers')
                    ->onDelete('cascade');

                $table->foreign('field_id')
                    ->references('id')->on('fields')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
        Schema::dropIfExists('field_subscriber');
    }
}
