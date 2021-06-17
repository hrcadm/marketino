<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerController;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CustomerTest extends TestCase {

    // use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample() {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function createCustomerWithUsedVatNumber() {

        $customerController = new CustomerController();

        $vatNumber = '01654960473';

        $q = [
            "first_name"      => "",
            "last_name"       => "Glover",
            "company_name"    => "Price-Kovacek",
            "vat_number"      => $vatNumber,
            "address"         => "755 Bartell Courts",
            'zip'             => '77',
            'einvoice_code'   => '12345',
            'customer_status' => 'Loost',
            'fiscal_number'   => '024982503457787'
        ];

        $request = new CustomerRequest($q);

        $response = $customerController->store($request);

        //dd($response);

    }


    /**
     * @test
     */
    public function shouldCreateCustommerWithAdditionalAddress() {

        $daAddress = "67424 Virgie Park";
        $daName = 'Lang Inc';
        $daZip = 39044;
        $daCity = 'Egna';
        $daRegion = 'AO';
        $daNotes = 'Additional address notes';


        $request = [
            "customer_status"       => "LEAD",
            "sale_channel"          => "PHONE",
            "first_name"            => "Proba 11",
            "last_name"             => "test",
            "address"               => "67424 Virgie Park",
            "address_additional"    => 'second address',
            "country_code"          => "IT",
            "zip"                   => "13160",
            "city"                  => "Dalestad",
            "region"                => "South Royal",
            'deliveryAddress' => 'on',
            "da_first_name"         => "TestCustomer",
            "da_last_name"          => "test",
            "da_phone"              => "18747105681",
            "da_address"            => $daAddress,
            "da_address_additional" => null,
            "da_country_code"       => "IT",
            "da_zip"                => $daZip,
            "da_city"               => $daCity,
            "da_region"             => $daRegion,
            "da_company_name"       => $daName,
            "da_note"               => $daNotes,
            "company_name"          => "New Test Company3",
            "activity_type_id"      => "18ea1414-0cb7-449e-b035-760775d79638",
            "vat_number"            => "01114601006",
            "fiscal_number"         => null,
            "phone"                 => "18747105681",
            "email"                 => "testCust@mraz.com",
            "company_type"          => "INDIVIDUAL",
            "company_date"          => "2020-10-29",
            "legal_contact"         => null,
            "iban"                  => null,
            "iban_name"             => null,
            "note"                  => null,
            "einvoice_code"         => null
        ];

        self::getValidatorErrorMsg($request);

        $controller = new CustomerController();
        $customer = $controller->addCustomer($request);
        self::assertEquals('second address',$customer->address_additional);
        $additionalAdd = DB::table('delivery_addresses')->where('customer_id', $customer->id)->first();
        self::assertEquals($additionalAdd->customer_id, $customer->id);

        self::assertEquals($daAddress, $additionalAdd->address);
        self::assertEquals($daZip, $additionalAdd->zip);
        self::assertEquals($daCity, $additionalAdd->city);
        self::assertEquals($daRegion, $additionalAdd->region);
        self::assertEquals($daNotes, $additionalAdd->note);
        self::assertEquals($daName, $additionalAdd->company_name);
    }

    private static function getValidatorErrorMsg(array $data): void {

        $request = new CustomerRequest();
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);
        $msg = $validator->getMessageBag();

        if ($msg->getMessages()) {
            dd($msg);
        }
    }

}
