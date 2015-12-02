<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerLedgersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_ledgers', function($t){
			$t->bigIncrements('id');
			$t->bigInteger('customer_id');
			$t->date('date');
			$t->string('transaction_type');
			$t->text('desc');
			$t->string('invoice_no');
			$t->decimal('amount', 15, 2);
			$t->decimal('balance', 15, 2);

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customer_ledgers');
	}

}
