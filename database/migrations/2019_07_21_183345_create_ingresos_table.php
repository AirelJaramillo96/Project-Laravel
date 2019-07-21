<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ingresos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idsupplier')->unsigned();
            $table->foreign('idsupplier')->references('id')->on('suppliers');
            $table->integer('iduser')->unsigned();
            $table->foreign('iduser')->references('id')->on('users');
            $table->string('type_voucher', 20);
            $table->string('serie_voucher',7)->nullable();
            $table->string('num_voucher',10);
            $table->dateTime('date_hour');
            $table->decimal('tax', 4, 2);
            $table->decimal('total', 11, 2);
            $table->string('state', 20);

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
        Schema::dropIfExists('ingresos');
    }
}
