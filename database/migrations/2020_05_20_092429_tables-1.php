<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tables1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->longText('value');
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('status')->default(1);
            $table->string('price')->nullable();
            $table->longText('settings')->nullable();
            $table->dateTime('date')->nullable();
        });

        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->string('status')->default(1);
            $table->longText('name')->nullable();
            $table->longText('note')->nullable();
            $table->string('url')->nullable();
            $table->string('url_slug')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->longText('settings')->nullable();
            $table->integer('order')->default(0);
            $table->dateTime('date')->nullable();
        });

        Schema::create('portfolio', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->longText('settings')->nullable();
            $table->integer('order')->default(0);
            $table->dateTime('date')->nullable();
        });

        Schema::create('support', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1);
            $table->string('support_id')->default(0);
            $table->integer('user')->nullable();
            $table->string('priority')->default('low');
            $table->string('type')->default('enquiry');
            $table->string('from')->nullable();
            $table->string('category')->default('general');
            $table->string('viewed')->default(0);
            $table->longText('settings')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('updated_on')->nullable();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('url');
            $table->string('title')->nullable();
            $table->string('status')->default(0);
            $table->string('type')->default('internal');
            $table->string('image')->nullable();
            $table->longText('settings')->nullable();
            $table->integer('order')->default(0);
            $table->integer('total_views')->default(0);
            $table->dateTime('date')->nullable();
            $table->dateTime('edited_on')->nullable();
        });

        Schema::create('pages_categories', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->integer('status')->default(1);
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->dateTime('date')->nullable();
            $table->dateTime('edited_on')->nullable();
        });

        Schema::create('support_replies', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->nullable();
            $table->string('support_id')->nullable();
            $table->string('from')->nullable();
            $table->string('viewed')->default(0);
            $table->longText('settings')->nullable();
            $table->dateTime('date')->nullable();
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->string('price')->nullable();
            $table->string('package')->nullable();
            $table->string('gateway')->default(0);
            $table->dateTime('date')->nullable();
        });

        Schema::create('track', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->integer('dyid')->nullable();
            $table->string('visitor_id')->nullable();
            $table->string('type');
            $table->string('country')->nullable();
            $table->string('ip');
            $table->string('os');
            $table->string('browser');
            $table->string('referer');
            $table->string('count');
            $table->dateTime('date');
        });

        Schema::create('users_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->string('what');
            $table->string('ip');
            $table->string('os');
            $table->string('browser');
            $table->dateTime('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('links');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('support');
        Schema::dropIfExists('support_replies');
        Schema::dropIfExists('portfolio');
        Schema::dropIfExists('track');
        Schema::dropIfExists('site_logs');
        Schema::dropIfExists('users_logs');
    }
}
