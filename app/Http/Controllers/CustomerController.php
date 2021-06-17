<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerEmail;
use App\Models\CustomerPhone;
use App\Models\DeliveryAddress;
use Arr;
use Illuminate\View\View;

class CustomerController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request) {
        if($request->ref === '3cx' && $request->number) {
            $customers = Customer::whereHas('phones', function ($query) use ($request) {
                $query->where('customer_phones.phone', 'LIKE',  '%'.$request->number);
            })->get();

            if($customers->count() === 0) {
                return redirect()->route('customers.create', ['phone' => $request->number]);
            } elseif($customers->count() === 1) {
                $customer = $customers->first();
                return redirect()->route('customers.show', $customer);
            }
            return view('pages.customers.3cx', compact('customers'));
        }
        return view('pages.customers.index', [
            'customers' => Customer::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create() {
        return view('pages.customers.create');
    }

    public function store(CustomerRequest $request) {
        $customer = $this->addCustomer($request->all());
        return redirect()->route('customers.show', $customer);
    }

    public function addCustomer(array $customerData) {

        /* @var Customer $customer */
        $customer = tap(new Customer($customerData))->save();
        $phone = Arr::get($customerData, 'phone');
        $email = Arr::get($customerData, 'email');
        $deliveryAddress = Arr::get($customerData, 'deliveryAddress');
        $customer->user_id = \Auth::id();
        $customer->save();

        if ($phone) {
            $customer->phones()->save(new CustomerPhone([
                'phone'      => $phone,
                'is_default' => true
            ]));
        }

        if ($email) {
            $customer->emails()->save(new CustomerEmail([
                'email'      => $email,
                'is_default' => true
            ]));
        }

        if ($deliveryAddress) {
            $customer->deliveryAddress()->save(new DeliveryAddress([
                'first_name'         => Arr::get($customerData, 'da_first_name'),
                'last_name'          => Arr::get($customerData, 'da_last_name'),
                'company_name'       => Arr::get($customerData, 'da_company_name'),
                'phone'              => Arr::get($customerData, 'da_phone'),
                'address'            => Arr::get($customerData, 'da_address'),
                'address_additional' => Arr::get($customerData, 'da_address_additional'),
                'zip'                => Arr::get($customerData, 'da_zip'),
                'city'               => Arr::get($customerData, 'da_city'),
                'region'             => Arr::get($customerData, 'da_region'),
                'country_code'       => Arr::get($customerData, 'da_country_code'),
                'note'               => Arr::get($customerData, 'da_note')
            ]));
        }

        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @param string $tab
     */
    public function show(Customer $customer, string $tab = 'details') {
        $deliveryAddress = data_get($customer->deliveryAddress,0);///first
        //  return view('pages.customers.show', compact('customer'));
        return view("pages.customers.tab.$tab", ['customer' => $customer,'deliveryAddress' => $deliveryAddress]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     */
    public function edit(Customer $customer) {
        $deliveryAddress = data_get($customer->deliveryAddress,0);///first
        return view('pages.customers.edit', ['customer' => $customer,'deliveryAddress' => $deliveryAddress]);
    }

    public function update(CustomerRequest $request, string $id) {
        //from checkbox--ako nisi dobio gdpr ili newsletter
        $customer = Customer::findOrFail($id);

        $phone = $request->get('phone');
        $email = $request->get('email');
        $saleChannel = $request->get('sale_channel');
        $customerStatus = $request->get('customer_status');
        $companyType = $request->get('company_type');
        $deliveryAddress = $request->get('deliveryAddress');/// on | null
        $gdpr = $request->get('gdpr', null);
        $newsletter = $request->get('newsletter', null);


        if ($phone) {
            $customerPhone = CustomerPhone::where('customer_id', $id)->first();
            if ($customerPhone) {
                $customerPhone->phone = $phone;
                $customerPhone->save();
            } else {
                tap(new CustomerPhone([
                    'phone'       => $phone,
                    'customer_id' => $id,
                    'is_default'  => true
                ]))->save();
            }
        }
        if ($email) {
            $customerEmail = CustomerEmail::where('customer_id', $id)->first();
            if ($customerEmail /*&& $email !== $customerEmail->email*/) {
                $customerEmail->email = $email;
                $customerEmail->save();

                //dd(__LINE__);
            } else {
                tap(new CustomerEmail([
                    'email'       => $email,
                    'customer_id' => $id,
                    'is_default'  => true
                ]))->save();
            }
        }


        if ($deliveryAddress) {
            $da = $customer->deliveryAddress()->first();

            if ($da) {
                $da->fill($request->get('da'));
            } else {
                $da = new DeliveryAddress($request->get('da'));
            }
            $customer->deliveryAddress()->save($da);
        }

        if ($saleChannel) {
            $customer->sale_channel = $saleChannel;
        }
        if ($customerStatus) {
            $customer->customer_status = $customerStatus;
        }
        if ($companyType) {
            $customer->company_type = $companyType;
        }
        if (!$newsletter) {
            $customer->newsletter = false;
        }

        if (!$gdpr) {
            $customer->gdpr = false;
        }
        if ($customer->fill($request->all())->save()) {
            return redirect()->route('customers.show', $customer);
        }
        return redirect()->route('customers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Customer $customer
     */
    public function destroy(Customer $customer) {
        //
    }
}
