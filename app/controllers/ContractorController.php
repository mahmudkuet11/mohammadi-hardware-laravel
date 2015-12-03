<?php

class ContractorController extends BaseController{
	public function postAddContractor(){

		$name 		= Input::get('name');
		$phone 		= Input::get('phone');
		$address 	= Input::get('address');
		$opening	= Input::get('opening_balance');

		DB::beginTransaction();
		try{

			$id = DB::table('contractors')->insertGetId(array(
						'name'		=>	$name,
						'phone'		=>	$phone == null ? '' : $phone,
						'address'	=>	$address == null ? '' : $address
				  ));
			DB::table('contractor_ledgers')->insert(array(
					'contractor_id'	=>	$id,
					'date'	=>	date('Y-m-d'),
					'desc'	=>	'Opening balance',
					'bill_no'	=>	'',
					'amount'	=>	$opening
				));

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}

	}

	public function postAddContractorInvoice(){

		$contractor_id = Input::get('contractor_id');

		$date = Input::get('date');
		$site_address = Input::get('site_address') == null ? '' : Input::get('site_address');
		$bill_no = Input::get('bill_no') == null ? '' : Input::get('bill_no');
		$product_name = Input::get('product_name') == null ? '' : Input::get('product_name');
		$shade = Input::get('shade') == null ? '' : Input::get('shade');
		$size = Input::get('size') == null ? '' : Input::get('size');
		$mrp = Input::get('mrp');
		$quantity = Input::get('quantity');
		$less = Input::get('less') == null ? 0 : Input::get('less');
		$amount = Input::get('amount');


		DB::beginTransaction();
		try{

			DB::table('contractor_invoice')->insert(array(
				'date'	=>	$date,
				'contractor_id'	=>	$contractor_id,
				'site_address'	=>	$site_address,
				'bill_no'	=>	$bill_no,
				'product_name'	=>	$product_name,
				'shade'	=>	$shade,
				'size'	=>	$size,
				'mrp'	=>	$mrp,
				'quantity'	=>	$quantity,
				'less'	=>	$less,
				'amount'	=>	$amount
			));

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}

	}

	public function postContractorPayment(){
		$contractor_id = Input::get('contractor_id');
		$date = Input::get('date');
		$desc = Input::get('desc');
		$amount = Input::get('amount');

		DB::beginTransaction();
		try{

			DB::table('contractor_payment')->insert(array(
				'contractor_id'	=>	$contractor_id,
				'date'	=>	$date,
				'desc'	=>	$desc,
				'amount'	=>	$amount,
			));

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}


	}
}