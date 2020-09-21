<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToUserViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_views', function (Blueprint $table) {
            $table->index([
                'viewer_id',
            ]);
            $table->index([
                'viewed_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_views', function (Blueprint $table) {
            $table->dropIndex([
                'viewer_id',
            ]);
            $table->dropIndex([
                'viewed_id',
            ]);
        });
    }
}
