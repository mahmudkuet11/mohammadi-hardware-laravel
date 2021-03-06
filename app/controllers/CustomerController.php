<?php

class CustomerController extends BaseController{
	public function postAddCustomer(){
		$name 		= Input::get('name');
		$phone 		= Input::get('phone');
		$address 	= Input::get('address');
		$opening	= Input::get('opening_balance');

		DB::beginTransaction();
		try{

			$id = DB::table('customers')->insertGetId(array(
						'name'		=>	$name,
						'phone'		=>	$phone == null ? '' : $phone,
						'address'	=>	$address == null ? '' : $address
				  ));

			DB::table('customer_ledgers')->insert(array(
					'customer_id'	=>	$id,
					'date'	=>	date('Y-m-d'),
					'transaction_type'	=>	'dr',
					'desc'	=>	'Opening balance',
					'invoice_no'	=>	'',
					'amount'	=>	$opening,
					'balance'	=>	$opening,
				));

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}
	}

	public function getAllCustomer(){
		return json_encode(DB::table('customers')->get());
	}


	public function postInsertCustomerLedger(){
		$date = Input::get('date');
		$desc = Input::get('desc');
		$invoice_no = Input::get('invoice_no');
		$amount = Input::get('amount');
		$customer_id = Input::get('customer_id');
		$type = Input::get('type');

		DB::beginTransaction();
		try{

			$balance = DB::select(DB::raw("select balance from customer_ledgers where customer_id=". $customer_id ." order by id desc limit 1"))[0]->balance;
			if($type == "dr"){
				$balance += $amount;
			}else{
				$balance -= $amount;
			}

			DB::table('customer_ledgers')->insert(array(
					'customer_id'	=>	$customer_id,
					'date'	=>	$date,
					'transaction_type'	=>	$type,
					'desc'	=>	$desc == null ? '' : $desc,
					'invoice_no'	=>	$invoice_no == null ? '' : $invoice_no,
					'amount'	=>	$amount,
					'balance'	=>	$balance
				));

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}

	}


	public function getCustomerTransaction(){
		$start = Input::get('start');
		$end = Input::get('end');
		$supplier_id = Input::get('customer_id');

		$dr = DB::table('customer_ledgers')->where('customer_id', $supplier_id)->where('date', '<', $start)->where('transaction_type', 'dr')->sum('amount');
		$cr = DB::table('customer_ledgers')->where('customer_id', $supplier_id)->where('date', '<', $start)->where('transaction_type', 'cr')->sum('amount');
		$opening_balance = $dr - $cr;

		$dr_trans = DB::table('customer_ledgers')->where('transaction_type', 'dr')->where('id', '>', '1')->whereBetween('date', array($start, $end))->get();

		$cr_trans = DB::table('customer_ledgers')->where('transaction_type', 'cr')->whereBetween('date', array($start, $end))->get();

		$res = array();
		if(sizeof($dr_trans) > sizeof($cr_trans)){
			$len = sizeof($dr_trans);
			for($i = 0; $i < $len; $i++){
				if($i+1 <= sizeof($cr_trans)){
					array_push($res, array(
						'date_dr'		=>	$dr_trans[$i]->date,
						'desc_dr'		=>	$dr_trans[$i]->desc,
						'invoice_no'	=>	$dr_trans[$i]->invoice_no,
						'amount_dr'		=>	$dr_trans[$i]->amount,
						'date_cr'		=>	$cr_trans[$i]->date,
						'desc_cr'		=>	$cr_trans[$i]->desc,
						'amount_cr'		=>	$cr_trans[$i]->amount
					));
				}else{
					array_push($res, array(
						'date_dr'		=>	$dr_trans[$i]->date,
						'desc_dr'		=>	$dr_trans[$i]->desc,
						'invoice_no'	=>	$dr_trans[$i]->invoice_no,
						'amount_dr'		=>	$dr_trans[$i]->amount,
						'date_cr'		=>	'',
						'desc_cr'		=>	'',
						'amount_cr'		=>	'0.0'
					));
				}
				
			}
		}else{
			$len = sizeof($cr_trans);
			for($i = 0; $i < $len; $i++){
				if($i+1 <= sizeof($dr_trans)){
					array_push($res, array(
						'date_dr'		=>	$dr_trans[$i]->date,
						'desc_dr'		=>	$dr_trans[$i]->desc,
						'invoice_no'	=>	$dr_trans[$i]->invoice_no,
						'amount_dr'		=>	$dr_trans[$i]->amount,
						'date_cr'		=>	$cr_trans[$i]->date,
						'desc_cr'		=>	$cr_trans[$i]->desc,
						'amount_cr'		=>	$cr_trans[$i]->amount
					));
				}else{
					array_push($res, array(
						'date_dr'		=>	'',
						'desc_dr'		=>	'',
						'invoice_no'	=>	'',
						'amount_dr'		=>	'0.0',
						'date_cr'	=>	$cr_trans[$i]->date,
						'desc_cr'	=>	$cr_trans[$i]->desc,
						'amount_cr'	=>	$cr_trans[$i]->amount
					));
				}
				
			}
		}
		
		return json_encode($res);

	}

}