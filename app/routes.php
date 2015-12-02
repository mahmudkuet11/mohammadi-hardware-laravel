<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::post('supplier/add', array(
		'as'	=>	'postAddSupplier',
		'uses'	=>	'SupplierController@postAddSupplier'
	));

Route::get('supplier/all', array(
		'as'	=>	'getAllSupplier',
		'uses'	=>	'SupplierController@getAllSupplier'
	));

Route::post('supplier/ledger/insert', array(
		'as'	=>	'postInsertSupplierLedger',
		'uses'	=>	'SupplierController@postInsertSupplierLedger'
	));

Route::get('supplier/transaction', array(
		'as'	=>	'getSupplierTransaction',
		'uses'	=>	'SupplierController@getSupplierTransaction'
	));

Route::post('customer/add', array(
		'as'	=>	'postAddCustomer',
		'uses'	=>	'CustomerController@postAddCustomer'
	));

Route::get('customer/all', array(
		'as'	=>	'getAllCustomer',
		'uses'	=>	'CustomerController@getAllCustomer'
	));

Route::post('customer/ledger/insert', array(
		'as'	=>	'postInsertCustomerLedger',
		'uses'	=>	'CustomerController@postInsertCustomerLedger'
	));

Route::get('customer/transaction', array(
		'as'	=>	'getCustomerTransaction',
		'uses'	=>	'CustomerController@getCustomerTransaction'
	));

Route::post('personal-account/party/add', array(
		'as'	=>	'postPersonalAccountPartyAdd',
		'uses'	=>	'PersonalAccountController@postPersonalAccountPartyAdd'
	));

Route::get('personal-account/party/all', array(
		'as'	=>	'getAllPersonalAccountParty',
		'uses'	=>	'PersonalAccountController@getAllPersonalAccountParty'
	));

Route::post('personal-account/ledger/insert', array(
		'as'	=>	'postPersonalAccountLedgerInsert',
		'uses'	=>	'PersonalAccountController@postPersonalAccountLedgerInsert'
	));

Route::get('personal-account/transaction/get', array(
		'as'	=>	'getPersonlAccountTransaction',
		'uses'	=>	'PersonalAccountController@getPersonlAccountTransaction'
	));