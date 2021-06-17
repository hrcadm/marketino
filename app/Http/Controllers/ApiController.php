<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class ApiController extends Controller
{
    public function THREECXSearch(Request $request) {
    	// if($request->apiKey !== config('3cx.api_key'))
    	// 	return response()->json(['error' => 'Unauthenticated.'], 401);
		$data['contact']['id'] = NULL;
		$data['contact']['firstname'] = NULL;
		$data['contact']['lastname'] = NULL;
		$data['contact']['company'] = NULL;
		$data['contact']['email'] = NULL;
		$data['contact']['businessphone'] = NULL;
		$data['contact']['url'] = route('customers.index', ['ref' => '3cx', 'number' => $request->number]);

    	$customers = Customer::whereHas('phones', function ($query) use ($request) {
    		$query->where('customer_phones.phone', 'LIKE',  '%'.$request->number);
    	})->get();

    	if($customers->count() === 1) {
    		$customer = $customers->first();
    		$data['contact']['id'] = $customer->id;
    		$data['contact']['firstname'] = $customer->first_name;
    		$data['contact']['lastname'] = $customer->last_name;
    		$data['contact']['company'] = $customer->company_name;
    		$data['contact']['email'] = ($email = $customer->emails->first()) ? $email->email : NULL;
    		$data['contact']['businessphone'] = ($phone = $customer->phones->first()) ? $phone->phone : NULL;
    	}

    	return $data;
    }
}
