<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalLedgersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personal_ledgers', function($t){
			$t->bigIncrements('id');
			$t->date('date');
			$t->integer('party_id');
			$t->text('desc');
			$t->decimal('amount', 15, 2);
			$t->string('method');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('personal_ledgers');
	}

}
