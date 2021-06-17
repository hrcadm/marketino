<?php

namespace Tests\Unit;

use App\Enum\QuotePackageDescription;
use App\Http\Controllers\QuoteController;
use App\Models\Customer;
use App\Models\QuoteRequest;
use App\Models\SaleItemPrice;
use App\Models\User;
use Arr;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuoteTest extends TestCase {
    use WithFaker;
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testMakeQuote() {

        $saleItem = SaleItemPrice::where('short_id', SaleItemPrice::M1_PROMO)->first();

        $saleItem2 = SaleItemPrice::where('short_id', SaleItemPrice::CR_PROMO)->first();

        $customerId = Customer::inRandomOrder()->first()->id;

        $note = $this->faker->realText();

        $quote = (new QuoteController())->makeQuote([
            'customer_id' => $customerId,
            'note'        => $note,
            'items'       => [
                [
                    'sale_item_price' => $saleItem,
                    'quantity'        => random_int(1, 5),
                    'note'            => $this->faker->realText(80)
                ],
                [
                    'sale_item_price' => $saleItem2,
                    'quantity'        => random_int(1, 5),
                    'note'            => $this->faker->realText(80)
                ]
            ]
        ]);

        $attributes = $quote->attributesToArray();
        self::assertEquals(Arr::get($attributes, 'customer_id'), $customerId);
    }

    /**
     * @test
     */
    public function shouldMakeQuoteAndSaveToQuoteRequestTableFromHub() {

        $user = User::inRandomOrder()->first();
        Auth()->setUser($user);

        $customerId = Customer::inRandomOrder()->first()->id;

        $url = 'http://127.0.0.1:8000/quotes/' . $customerId;
        // $url = 'quotes/'.$customerId;

        $response = $this->call('POST', $url, array(

            'marketinoOne'     => [
                'M1-PROMO'  => 1,
                'M1-FINALE' => 1,
                'M1-ROTAM'  => null
            ],
            'marketinoOneNote' => QuotePackageDescription::M_HNB,
            'cardReader'       => [
                'CR-PROMO' => null
            ],
            "cardReaderNote"   => QuotePackageDescription::CR_PLATINUM,
            "setup"            => [
                "SETUP-PROMO" => 1
            ]
        ));

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('message', 'Quote sent!');

        $quoteRequestFromDB = QuoteRequest::where('customer_id', $customerId)->first();

        $attributes = $quoteRequestFromDB->attributesToArray();

        self::assertEquals($user->id, Arr::get($attributes, 'user_id'));
        self::assertFalse(Arr::get($attributes, 'from_web'));

    }

    /**
     * @test
     */
    public function shouldReturnSuccessMessageForSendGrenkeEmailFromHub() {

        $user = User::inRandomOrder()->first();
        Auth()->setUser($user);

        $customerEmail = Customer::inRandomOrder()->first()->email()->email;


        $quoteConreoller = new QuoteController();

        $r = $quoteConreoller->sendGrenkeEmail($customerEmail);

        $message = $r->getSession()->get('message');
        self::assertEquals('Grenke mail sent to ' . $customerEmail, $message);

    }

    /**
     * @test
     */
    public function shouldReturnErrorMessageForSendGrenkeEmailFromHub() {

        $user = User::inRandomOrder()->first();
        Auth()->setUser($user);
        $quoteConreoller = new QuoteController();

        $r = $quoteConreoller->sendGrenkeEmail();
        $errorArr = $r->getSession()->get('errors')->toArray();
        self::assertEquals('Customer has no email', $errorArr['email'][0]);
    }
}
