<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Updates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        # Update 1.0.1
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'button')) {
                $table->dropColumn('button');
            }
        });

        if (!Schema::hasTable('skills')) {
            Schema::create('skills', function (Blueprint $table) {
                $table->id();
                $table->integer('user');
                $table->string('name')->nullable();
                $table->string('bar')->default(0);
                $table->longText('settings')->nullable();
                $table->integer('position')->default(0);
                $table->dateTime('date')->nullable();
            });
        }

        # Update 1.0.2

        if (!Schema::hasTable('pending_payments')) {
            Schema::create('pending_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('user');
                $table->integer('status')->default(0);
                $table->string('email')->nullable();
                $table->string('name')->nullable();
                $table->string('bankName')->nullable();
                $table->string('proof')->nullable();
                $table->string('ref')->nullable();
                $table->integer('package')->nullable();
                $table->string('duration')->nullable();
                $table->string('type')->default('bank');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('track_links')) {
            Schema::create('track_links', function (Blueprint $table) {
                $table->id();
                $table->integer('user')->nullable();
                $table->string('type')->default('social');
                $table->string('slug')->nullable();
                $table->string('visitor_id')->nullable();
                $table->string('country')->nullable();
                $table->string('ip');
                $table->string('os');
                $table->string('browser');
                $table->string('views');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('linker')) {
            Schema::create('linker', function (Blueprint $table) {
                $table->id();
                $table->string('url')->nullable();
                $table->string('slug')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->after('active')->nullable();
            }
            if (!Schema::hasColumn('users', 'domain')) {
                $table->string('domain')->after('settings')->default('main');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'facebook_id')) {
                $table->string('facebook_id')->after('active')->nullable();
            }
        });

        Schema::table('payment', function (Blueprint $table) {
            if (!Schema::hasColumn('payment', 'name')) {
                $table->string('name')->after('user')->nullable();
            }
            if (!Schema::hasColumn('payment', 'email')) {
                $table->string('email')->after('name')->nullable();
            }
            if (!Schema::hasColumn('payment', 'duration')) {
                $table->string('duration')->after('email')->nullable();
            }
            if (!Schema::hasColumn('payment', 'ref')) {
                $table->string('ref')->after('duration')->nullable();
            }
            if (!Schema::hasColumn('payment', 'currency')) {
                $table->string('currency')->after('price')->nullable();
            }
            if (!Schema::hasColumn('payment', 'package_name')) {
                $table->string('package_name')->after('currency')->nullable();
            }
            if (Schema::hasColumn('payment', 'price')) {
                $table->float('price')->default(0)->change();
            }
        });

        # Update 1.0.5

        if (!Schema::hasTable('domains')) {
            Schema::create('domains', function (Blueprint $table) {
                $table->id();
                $table->integer('status')->default(0);
                $table->string('scheme')->nullable();
                $table->string('host')->nullable();
                $table->string('index_url')->nullable();
                $table->longText('settings')->nullable();
                $table->timestamps();
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
        //
    }
}
