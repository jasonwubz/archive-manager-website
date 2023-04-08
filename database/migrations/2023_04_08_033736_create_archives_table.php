<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('md5_checksum', 32);
            $table->string('original_name');
            $table->unsignedInteger('user_id')
                ->reference('id')
                ->on('users')
                ->nullable(); // nullable to allow anonymous users
            $table->unsignedInteger('times_downloaded')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archives');
    }
}
