<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_results', function (Blueprint $table) {
            //

            $table->foreign('task_id')            
                    ->references('id')               
                    ->on('tasks')                   
                    ->onDelete('cascade');
            $table->foreign('added_by')            
                    ->references('id')               
                    ->on('users')                   
                    ->onDelete('cascade');
            $table->foreign('last_updated_by')            
                    ->references('id')               
                    ->on('users')                   
                    ->onDelete('cascade'); 

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_results', function (Blueprint $table) {
            //
        });
    }
};
