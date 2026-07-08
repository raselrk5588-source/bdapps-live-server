<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstallApps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('install_apps', function (Blueprint $table) {
            $table->string('ussd_code')->nullable()->default(null)->after("category_id");
            $table->string('sms_keyword')->nullable()->default(null)->after("ussd_code");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::table('install_apps', function (Blueprint $table) {
            $table->string('ussd_code')->nullable()->default(null);
            $table->string('sms_keyword')->nullable()->default(null);
        });
    }
}
