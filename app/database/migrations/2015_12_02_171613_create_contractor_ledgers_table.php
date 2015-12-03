<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorLedgersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractor_ledgers', function($t){
			$t->bigIncrements('id');
			$t->date('date');
			$t->integer('contractor_id');
			$t->text('desc');
			$t->string('bill_no');
			$t->decimal('amount', 15, 2);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('contractor_ledgers');
	}

}
