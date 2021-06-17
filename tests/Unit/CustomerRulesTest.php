<?php

namespace Tests\Unit;

use App\Http\Requests\CustomerRequest;
use App\Models\ActivityType;
use App\Rules\EInvoiceCodeIt;
use App\Rules\FiscalOrVatNumberIt;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CustomerRulesTest extends TestCase
{

    /**
     * @test
     */
    public function customerRulesShouldFails()
    {

        $fiscalNumber = 'RSSMRA80A01F205';
        $vatNumber = '02498250342';
        $q = [
            "first_name"      => "",
            "last_name"       => "Glover",
            "company_name"    => "Price-Kovacek",
            "vat_number"      => $vatNumber,
            "address"         => "755 Bartell Courts",
            'zip'             => '77',
            'einvoice_code'   => '12345',
            'customer_status' => 'Loost',
            'fiscal_number'   => '024982503457787',
            'company_date' => '21.10.2120'
        ];

        $attributes = $q;
        $request = new CustomerRequest();
        $rules = $request->rules();

        $validator = Validator::make($attributes, $rules);
        $fails = $validator->fails();

        $msg = $validator->getMessageBag();

        $this->assertEquals('The company date must be a date before or equal to today.', $msg->first('company_date'));

        $this->assertEquals('The first name field is required.', $msg->first('first_name'));

        $this->assertEquals('The vat number must be valid italian vat number (Partita IVA).', $msg->first('vat_number'));
        $this->assertEquals('The fiscal number must be valid italian fiscal number (Codice Fiscale) or vat number (Partita IVA).', $msg->first('fiscal_number'));
        $this->assertEquals('The zip must be 5 characters.', $msg->first('zip'));
        $this->assertEquals('validation.enum_value', $msg->first('customer_status'));
        # $this->assertEquals('The selected einvoice code is invalid.', $msg->first('einvoice_code'));

        self::assertEquals(true, $fails);
    }

    /**
    * @test
    */
    public function fiscalNumberShouldAcceptVatCode(){

        $fiscalNumber = 'RSSMRA80A01F205X';
        $vatNumber = '02498250345';

        $fiscalNumbervalidator = new FiscalOrVatNumberIt();

        $isValidFiscalCode = $fiscalNumbervalidator->passes('fiscal_number',$fiscalNumber);

        self::assertTrue($isValidFiscalCode);

        $isValidFiscalCode2 = $fiscalNumbervalidator->passes('fiscal_number',$vatNumber);

        self::assertTrue($isValidFiscalCode2);

        $isValidFiscalCode2 = $fiscalNumbervalidator->passes('fiscal_number','12345678789');
        self::assertFalse($isValidFiscalCode2);

    }

    /**
    * @test
    */
    public function validateEinvoiceCodeTest(){

        $pec = "testUser@mail.com";
        $sdi = '123q5a7';

        $einvoiceValidator = new EInvoiceCodeIt();

        self::assertTrue($einvoiceValidator->passes('PEC',$pec));
        self::assertTrue($einvoiceValidator->passes('SDI',$sdi));
        self::assertFalse($einvoiceValidator->passes('PEC','testTestUser'));
        self::assertFalse($einvoiceValidator->passes('SDI','12345678'));

    }

    /**
     * @test
     */
    public function customerRulesShouldPass()
    {

        $fiscalNumber = 'RSSMRA80A01F205X';
        $vatNumber = '02498250345';
        $q = [
            "client_status"      => "LEAD",
            "source"             => "PHONE",
            "first_name"         => "Drazen",
            "last_name"          => "Cvjetkovic",
            "address"            => null,
            "address_additional" => null,
            "country_code"       => "IT",
            "zip"                => null,
            "city"               => null,
            "region"             => null,
            "company_name"       => "New company",
            "activity_type_id"   => ActivityType::first()->id,
            "vat_number"         => $vatNumber,
            "fiscal_number"      => $fiscalNumber,
            "company_type"       => "INDIVIDUAL",
            "company_date"       => null,
            "legal_contact"      => null,
            "iban"               => null,
            "iban_name"          => null,
            "note"               => null,
            "einvoice_code"      => null,
        ];

        $attributes = $q;
        $request = new CustomerRequest();
        $rules = $request->rules();

        $validator = Validator::make($attributes, $rules);
        $msg = $validator->getMessageBag();
        $fails = $validator->fails();
        self::assertEquals(false, $fails);
    }
}
