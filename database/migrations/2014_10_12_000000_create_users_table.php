<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('email_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->longtext('about')->nullable();
            $table->string('avatar')->default('default.jpg');
            $table->string('banner')->nullable();
            $table->string('background')->nullable();
            $table->string('background_type')->default('main');
            $table->longText('button')->nullable();
            $table->string('package')->default('free');
            $table->dateTime('package_due')->nullable();
            $table->longText('settings')->nullable();
            $table->string('verified')->default(0);
            $table->longText('link_row')->nullable();
            $table->longText('menus')->nullable();
            $table->integer('role')->default(0);
            $table->integer('active')->default(1);
            $table->longText('socials')->nullable();
            $table->dateTime('activity')->nullable();
            $table->string('user_agent')->nullable();
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
        Schema::dropIfExists('users');
    }
}
