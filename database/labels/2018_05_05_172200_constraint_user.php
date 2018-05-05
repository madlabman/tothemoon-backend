<?php

use Illuminate\Support\Facades\DB;
use Vinelab\NeoEloquent\Facade\Neo4jSchema;
use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class ConstraintUser extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Neo4jSchema::label('User', function (Blueprint $label) {
            $label->unique('uuid');
            $label->unique('login');
            $label->unique('phone');
            $label->unique('promo_code');
            $label->unique('ref_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Neo4jSchema::dropIfExists('User');
    }

}
