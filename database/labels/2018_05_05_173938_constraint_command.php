<?php

use Vinelab\NeoEloquent\Schema\Blueprint;
use Vinelab\NeoEloquent\Migrations\Migration;

class ConstraintCommand extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Neo4jSchema::label('Command', function (Blueprint $label) {
            $label->unique('name');
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
        Neo4jSchema::dropIfExists('Command');
    }

}
