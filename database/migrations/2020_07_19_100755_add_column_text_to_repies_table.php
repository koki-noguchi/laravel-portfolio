<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTextToRepiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->text('reply_text')->nullable();
            $table->timestamps();
        });
        Schema::table('replies', function (Blueprint $table) {
            $table->text('reply_text')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('replies', function (Blueprint $table) {
            $table->dropColumn('reply_text');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
