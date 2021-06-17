<?php

namespace Tests\Feature;

use App\Http\Controllers\WebQuoteController;
use App\Http\Requests\WebQuoteRequest;
use App\Models\Customer;
use App\Models\QuoteRequest;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class WebQuoteTest extends TestCase {

    use WithFaker;
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample() {

        $url = 'https://test-hub.marketino.it/';
        $vatNumber = app('Faker')->vatNumberIt;
        $response = $this->call('POST', $url . 'api/web-quote', array(
            'quote_type'                 => WebQuoteController::QUOTE_TYPE_BUY,
            'm1_quantity'                => 1,
            'software_package'           => 'hair_and_beauty',
            'usage_place'                => 'inside',
            'card_reader_quantity'       => 2,
            'card_reader_plan'           => 'standard',
            'first_name'                 => $this->faker->firstName,
            'last_name'                  => $this->faker->firstName,
            'company'                    => $this->faker->company,
            'phone'                      => $this->faker->numerify('### ###-###-####'),
            'email'                      => $this->faker->email,
            'vat_number'                 => $vatNumber,
            'fiscal_number'              => app('Faker')->fiscalNumberIt,
            'address'                    => $this->faker->streetAddress,
            'zip'                        => $this->faker->numerify(str_repeat('#', 5)),
            'city'                       => $this->faker->city,
            'e_invoice_code'             => $this->faker->email,
            'accept_terms'               => true,
            'shipping_address'           => $this->faker->streetAddress,
            'shipping_zip'               => $this->faker->numerify(str_repeat('#', 5)),
            'shipping_city'              => $this->faker->city,
            'shipping_notes'             => $this->faker->realText(100),
            "setup_gratis"               => 'true',
            "newsletter"                 => 'true',
            'different_shipping_address' => 'false',

        ));

        $response->assertSessionHasNoErrors();
        $u = config('web_quote.success');
        $response->assertRedirect($u);

        $customerFromDB = Customer::where('vat_number', $vatNumber)->first()->id;
        $quoteRequestFromDB = QuoteRequest::where('customer_id', $customerFromDB)->first();
        $attributes = $quoteRequestFromDB->attributesToArray();
        self::assertTrue(Arr::get($attributes, 'from_web'));
        self::assertArrayHasKey('ip_address', $attributes);
    }

    /**
     * @test
     */
    public function shouldRedirectOnSuccess() {

        $vatNumber = '00000010215';
        $data = [
            "quote_type"                 => "buy",
            "setup_gratis"               => 'true',
            "newsletter"                 => 'true',
            'different_shipping_address' => 'false',
            "m1_quantity"                => "1",
            "card_reader_quantity"       => null,
            "software_package"           => "prime",
            "first_name"                 => "Maria",
            "last_name"                  => "Russel",
            "company"                    => "Has del address",
            "vat_number"                 => $vatNumber,
            "phone"                      => "7696770057",
            "email"                      => "dominique.olson@vandervort.biz",
            "fiscal_number"              => "RSSMRA80A01F205X",
            "address"                    => "34782 Lonie Ridges",
            "zip"                        => "23731",
            "region"                     => "Lake Edmondstad",
            "city"                       => "Powlowskishire",
            "country"                    => "italy",
            "e_invoice_code"             => "1234ert",
            "usage_place"                => "inside",
            "shipping_name"              => null,
            "shipping_surname"           => null,
            "shipping_phone"             => null,
            "shipping_address"           => null,
            "shipping_zip"               => null,
            "shipping_city"              => null,
            "shipping_region"            => null,
            "shipping_country"           => null,
            "shipping_notes"             => null,
            "accept_terms"               => "on",
        ];

        self::getValidatorErrorMsg($data);
        $response = $this->call('POST', '' . 'api/web-quote', $data);
        $u = config('web_quote.success');
        $response->assertRedirect($u);
    }

    private static function getValidatorErrorMsg(array $data): void {

        $request = new WebQuoteRequest();
        $rules = $request->rules();

        $validator = Validator::make($data, $rules);
        $msg = $validator->getMessageBag();
        if ($msg->getMessages()) {
            dd($msg);
        }
    }

    /**
     * @test
     */
    public function shouldRedirectOnError() {

        $vatNumber = '000000';
        $data = [
            "quote_type"           => "buy",
            "m1_quantity"          => "1",
            "card_reader_quantity" => null,
            "software_package"     => "prime",
            "first_name"           => "Maria",
            "last_name"            => "Russel",
            "company"              => "Stokes, Larkin and Bode",
            "vat_number"           => $vatNumber,
            "phone"                => "7696770057",
            "email"                => "dominique.olson@vandervort.biz",
            "fiscal_number"        => "RSSMRA80",
            "address"              => "34782 Lonie Ridges",
            "zip"                  => "23731",
            "region"               => "Lake Edmondstad",
            "city"                 => "Powlowskishire",
            "country"              => "italy",
            "e_invoice_code"       => "pec",
            "usage_place"          => "inside",
            "shipping_name"        => null,
            "shipping_surname"     => null,
            "shipping_phone"       => null,
            "shipping_address"     => null,
            "shipping_zip"         => null,
            "shipping_city"        => null,
            "shipping_region"      => null,
            "shipping_country"     => null,
            "shipping_notes"       => null,
            "accept_terms"         => "on",
        ];

        $response = $this->call('POST', '' . 'api/web-quote', $data);
        $u = config('web_quote.error');
        $response->assertRedirect($u);
    }

    /**
     * @test
     */
    public function shouldSendGrenkeEmail() {

        $vatNumber = '00000010215';
        $sdi = 'testUser@mail.com';
        $pec = '1234ert';
        $data = [
            "quote_type"                 => "lease",
            "m1_quantity"                => "1",
            "setup_gratis"               => 'false',
            "card_reader_quantity"       => null,
            "software_package"           => "prime",
            "first_name"                 => "Maria",
            "last_name"                  => "Russel",
            "company"                    => "Stokes, Larkin and Bode",
            "vat_number"                 => $vatNumber,
            "phone"                      => "7696770057",
            "email"                      => "dominique.olson@vandervort.biz",
            "fiscal_number"              => "RSSMRA80A01F205X",
            "address"                    => "34782 Lonie Ridges",
            "zip"                        => "23731",
            "region"                     => "Lake Edmondstad",
            "city"                       => "Powlowskishire",
            "country"                    => "italy",
            "e_invoice_code"             => "testUser@mail.com",
            "newsletter"                 => "false",
            "different_shipping_address" => "false",
            "usage_place"                => "inside",
            "shipping_name"              => null,
            "shipping_surname"           => null,
            "shipping_phone"             => null,
            "shipping_address"           => null,
            "shipping_zip"               => null,
            "shipping_city"              => null,
            "shipping_region"            => null,
            "shipping_country"           => null,
            "shipping_notes"             => null,
            "accept_terms"               => "on",
        ];

        self::getValidatorErrorMsg($data);
        $response = $this->call('POST', '' . 'api/web-quote', $data);
        $u = config('web_quote.success');
        $response->assertRedirect($u);
    }

    /**
     * @test
     */
    public function shouldNotSendGrenkeEmailIfCustomerEmailIsNull() {
        $vatNumber = '00000010215';
        $pec = '1234ert';
        $data = [
            "quote_type"           => "lease",
            "setup_gratis"         => 'false',
            "m1_quantity"          => "1",
            "card_reader_quantity" => null,
            "software_package"     => "prime",
            "first_name"           => "Maria",
            "last_name"            => "Russel",
            "company"              => "Stokes, Larkin and Bode",
            "vat_number"           => $vatNumber,
            "phone"                => "7696770057",
            "email"                => null,
            "fiscal_number"        => "RSSMRA80A01F205X",
            "address"              => "34782 Lonie Ridges",
            "zip"                  => "23731",
            "region"               => "Lake Edmondstad",
            "city"                 => "Powlowskishire",
            "country"              => "italy",
            "e_invoice_code"       => "pec",
            "sdi_value"            => $pec,
            "usage_place"          => "inside",
            "shipping_name"        => null,
            "shipping_surname"     => null,
            "shipping_phone"       => null,
            "shipping_address"     => null,
            "shipping_zip"         => null,
            "shipping_city"        => null,
            "shipping_region"      => null,
            "shipping_country"     => null,
            "shipping_notes"       => null,
            "accept_terms"         => "on",
        ];

        $response = $this->call('POST', '' . 'api/web-quote', $data);
        $u = config('web_quote.error');
        $response->assertRedirect($u);
    }

    /**
     * @test
     */
    public function shouldSaveWebQuoteAdditionalAddress() {

        $daAddress = '1125 Mills Plaza';
        $daName = 'Lang Inc';
        $daZip = 39044;
        $daCity = 'Egna';
        $daRegion = 'AO';
        $daNotes = 'Additional address notes';

        $data = [
            'quote_type'           => 'buy',
            'm1_quantity'          => '2',
            'software_package'     => 'prime',
            'setup_gratis'         => 'false',
            'usage_place'          => 'inside',
            'card_reader_quantity' => null,
            'card_reader_plan'     => null,
            'first_name'           => 'Drazen',
            'last_name'            => 'c',
            'company'              => 'Web Second address22',
            'vat_number'           => app('Faker')->vatNumberIt,
            'phone'                => '019101919001',
            'email'                => 'testCust@mraz.com',
            'fiscal_number'        => '60529090783',
            'address'              => 'Premantura',
            'zip'                  => 38020,
            'city'                 => 'Rabbi',
            'region'               => 'TN',
            'e_invoice_code'       => 1234567,
            'accept_terms'         => true,
            'newsletter'           => 'false',

            'different_shipping_address' => 'true',
            'shipping_address'           => $daAddress,
            'shipping_name'              => $daName,
            'shipping_zip'               => $daZip,
            'shipping_city'              => $daCity,
            'shipping_region'            => $daRegion,
            'shipping_notes'             => $daNotes

        ];

        $attributes = $data;
        $request = new WebQuoteRequest();
        $rules = $request->rules();

        $validator = Validator::make($attributes, $rules);
        $fails = $validator->fails();
        self::assertFalse($fails);

        $this->call('POST', '' . 'api/web-quote', $data);
        $customer = DB::table('customers')->where('company_name', 'DC-web')->first();

        self::assertEquals('DC-web', $customer->company_name);
        self::assertEquals('1234567', $customer->einvoice_code);
        self::assertEquals('TN', $customer->region);

        $additionalAdd = DB::table('delivery_addresses')->where('customer_id', $customer->id)->first();
        self::assertEquals($additionalAdd->customer_id, $customer->id);

        self::assertEquals($daAddress,$additionalAdd->address);
        self::assertEquals($daZip,$additionalAdd->zip );
        self::assertEquals($daCity,$additionalAdd->city);
        self::assertEquals($daRegion,$additionalAdd->region);
        self::assertEquals($daNotes,$additionalAdd->note);
        self::assertEquals($daName,$additionalAdd->company_name);


    }
}
