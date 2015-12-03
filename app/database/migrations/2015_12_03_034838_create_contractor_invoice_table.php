<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractorInvoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contractor_invoice', function($t){
			$t->bigIncrements('id');
			$t->date('date');
			$t->integer('contractor_id');
			$t->string('site_address');
			$t->string('bill_no');
			$t->string('product_name');
			$t->string('shade');
			$t->string('size');
			$t->decimal('mrp', 15, 2);
			$t->integer('quantity');
			$t->decimal('less', 15, 2);
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
		Schema::dropIfExists('contractor_invoice');
	}

}
