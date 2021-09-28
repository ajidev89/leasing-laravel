<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            
            $table->id();
            $table->string('nin')->unique();
            $table->string('user_id');
            $table->string('email');
            $table->string('name');
            $table->string('contact_no');
            $table->string('martial_status');
            $table->string('children');
            $table->string('former_address');
            $table->string('next_of_kin');
            $table->string('contact_no_next_of_kin');
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
        Schema::dropIfExists('tenants');
    }
}
