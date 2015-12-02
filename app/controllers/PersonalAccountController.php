<?php

class PersonalAccountController extends BaseController{

	public function postPersonalAccountPartyAdd(){
		$name 		= Input::get('name');
		$phone 		= Input::get('phone');
		$address 	= Input::get('address');
		$opening_balance = Input::get('opening_balance');

		DB::beginTransaction();

		try{

			$id = DB::table('parties')->insertGetId(array(
					'name'		=>	$name,
					'phone'		=>	$phone,
					'address'	=>	$address
				));
			DB::table('personal_ledgers')->insert(array(
					'date'	=>	date('Y-m-d'),
					'party_id'	=>	$id,
					'desc'	=>	"Opening Balance",
					'amount'	=>	$opening_balance,
					'method'	=>	"dr"
				));;

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}
	}

	public function getAllPersonalAccountParty(){
		return json_encode(DB::table('parties')->get());
	}

	public function postPersonalAccountLedgerInsert(){
		$party_id = Input::get('party_id');
		$date = Input::get('date');
		$desc = Input::get('desc');
		$amount = Input::get('amount');
		$method = Input::get('method');
		

		DB::beginTransaction();
		try{

			DB::table('personal_ledgers')->insert(array(
					'date'	=>	$date,
					'party_id'	=>	$party_id,
					'desc'	=>	$desc,
					'amount'	=>	$amount,
					'method'	=>	$method
				));

			DB::commit();
			return '1';

		}catch(\Exception $e){
			DB::rollback();
			return '0';
		}


	}

	public function getPersonlAccountTransaction(){
		$start = Input::get('start');
		$end = Input::get('end');
		$party_id = Input::get('party_id');

		$dr = DB::table('personal_ledgers')->where('party_id', $party_id)->where('date', '<', $start)->where('method', 'dr')->sum('amount');
		$cr = DB::table('personal_ledgers')->where('party_id', $party_id)->where('date', '<', $start)->where('method', 'cr')->sum('amount');
		$opening_balance = $dr - $cr;

		$dr_trans = DB::table('personal_ledgers')->where('method', 'dr')->where('id', '>', '1')->whereBetween('date', array($start, $end))->get();

		$cr_trans = DB::table('personal_ledgers')->where('method', 'cr')->whereBetween('date', array($start, $end))->get();

		$res = array();
		if(sizeof($dr_trans) > sizeof($cr_trans)){
			$len = sizeof($dr_trans);
			for($i = 0; $i < $len; $i++){
				if($i+1 <= sizeof($cr_trans)){
					array_push($res, array(
						'date_dr'		=>	$dr_trans[$i]->date,
						'desc_dr'		=>	$dr_trans[$i]->desc,
						'amount_dr'		=>	$dr_trans[$i]->amount,
						'date_cr'		=>	$cr_trans[$i]->date,
						'desc_cr'		=>	$cr_trans[$i]->desc,
						'amount_cr'		=>	$cr_trans[$i]->amount
					));
				}else{
					array_push($res, array(
						'date_dr'		=>	$dr_trans[$i]->date,
						'desc_dr'		=>	$dr_trans[$i]->desc,
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
						'amount_dr'		=>	$dr_trans[$i]->amount,
						'date_cr'		=>	$cr_trans[$i]->date,
						'desc_cr'		=>	$cr_trans[$i]->desc,
						'amount_cr'		=>	$cr_trans[$i]->amount
					));
				}else{
					array_push($res, array(
						'date_dr'		=>	'',
						'desc_dr'		=>	'',
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