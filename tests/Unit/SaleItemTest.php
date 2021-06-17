<?php

namespace Tests\Unit;

use App\Enum\SaleItemType;
use App\Enum\SaleVat;
use App\Models\SaleItem;
use Tests\TestCase;

class SaleItemTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testModelValidation()
    {
        $saleItem = new SaleItem();
        $validator = $saleItem->getValidator();
        self::assertTrue($validator->fails());

        $saleItem->name = 'Test Item';
        $saleItem->vat = SaleVat::VAT_22;
        $saleItem->type = SaleItemType::PHYSICAL_PRODUCT;

        $validator = $saleItem->getValidator();
        self::assertFalse($validator->fails());
    }

    public function testValidationReturnsNiceError()
    {

        $data = [
            'name' => 'Test Item',
            'price' => 10.99,
            'vat' => SaleVat::VAT_22,
            'type' => SaleItemType::PHYSICAL_PRODUCT
        ];

        $saleItem = new SaleItem($data);
        self::assertEmpty($saleItem->getValidator()->errors()->first());

        $data2 = $data;
        $data2['name'] = null;
        $saleItem = new SaleItem($data2);
        self::assertEquals('The name field is required.', $saleItem->getValidator()->errors()->first());

//        $data2 = $data;$data2['price'] = null;
//        $saleItem = new SaleItem($data2);
//        self::assertEquals('The price field is required.', $saleItem->getValidator()->errors()->first());
//
//        $data2 = $data;$data2['price'] = 'A';
//        $saleItem = new SaleItem($data2);
//        self::assertEquals('The price must be an integer or a decimal number.', $saleItem->getValidator()->errors()->first());

        $data2 = $data;
        $data2['vat'] = '10';
        try {
            new SaleItem($data2);
            self::assertTrue(false);
        } catch (\BenSampo\Enum\Exceptions\InvalidEnumMemberException $e) {
            self::assertStringContainsString('Cannot construct an instance of SaleVat using the value', $e->getMessage());
        }

        $data2 = $data;
        $data2['type'] = 'new cool item!';
        try {
            new SaleItem($data2);
            self::assertTrue(false);
        } catch (\BenSampo\Enum\Exceptions\InvalidEnumMemberException $e) {
            self::assertStringContainsString('Cannot construct an instance of SaleItemType using the value', $e->getMessage());
        }
    }
}
