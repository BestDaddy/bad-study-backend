<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->string('uuid')
                ->nullable();
            $table->unsignedBigInteger('model_id')
                ->nullable()
                ->default(0);
            $table->string('model_type');

            $table->unsignedBigInteger('user_id');

            $table->unsignedTinyInteger('position')
                ->nullable();

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
        Schema::dropIfExists('attachments');
    }
}
