<?php

namespace App\Http\Controllers;

use App\Enum\QuotePackageDescription;
use App\Enum\SaleChannel;
use App\Http\Requests\WebQuoteRequest;
use App\Mail\SendGrenke;
use App\Models\Customer;
use App\Models\QuoteRequest;
use App\Models\SaleItemPrice;
use Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Jobs\CreateNextStep;

//use App\Http\Requests\WebQuoteRequest;

class WebQuoteController extends Controller {

    public const QUOTE_TYPE_BUY = 'buy';
    public const QUOTE_TYPE_LEASE = 'lease';

    public const SOFTWARE_PACKAGE_PRIME = 'prime';
    public const SOFTWARE_PACKAGE_HNB = 'hair_and_beauty';

    public const USAGE_PLACE_INSIDE = 'inside';
    public const USAGE_PLACE_OUTSIDE = 'outside';

    public const CARD_READER_PLAN_STANDARD = 'standard';
    public const CARD_READER_PLAN_GOLD = 'gold';
    public const CARD_READER_PLAN_PLATINUM = 'platinum';

    private const ITEM_M1_PROMO_ID = 'M1-PROMO';
    private const ITEM_M1_PROMO_WITH_DISCOUNT_ID = 'M1-FINALE';
    //private const ITEM_M1_ROTTAZIONE_ID = 'M1-ROTAM';

    private const ITEM_CARD_READER_PROMO = 'CR-PROMO';

    private const ITEM_SETUP_GRATIS = 'SETUP-PROMO';


    public function webQuote(WebQuoteRequest $request) {

        //da li customer sa partita iva postoji u bazi
        $customer = Customer::where('vat_number', $request->get('vat_number'))->first();

        if (!$customer) {
            $customer = (new CustomerController())->addCustomer([
                'first_name'    => $request->get('first_name'),
                'last_name'     => $request->get('last_name'),
                'company_name'  => $request->get('company'),
                'vat_number'    => $request->get('vat_number'),
                'fiscal_number' => $request->get('fiscal_number'),
                'address'       => $request->get('address'),
                'zip'           => $request->get('zip'),
                'city'          => $request->get('city'),
                'region'        => $request->get('region'),
                'gdpr'          => $request->get('accept_terms'),
                'newsletter'    => $request->get('newsletter', 'false') === 'true',
                'sale_channel'  => SaleChannel::WEB,
                'einvoice_code' => $request->get('e_invoice_code'),
                'phone'         => $request->get('phone'),
                'email'         => $request->get('email'),

                'deliveryAddress' => $request->get('different_shipping_address',
                        'false') === 'true',
                'da_company_name'    => $request->get('shipping_name'),
                'da_address'         => $request->get('shipping_address'),
                'da_zip'             => $request->get('shipping_zip'),
                'da_city'            => $request->get('shipping_city'),
                'da_note'            => $request->get('shipping_notes'),
                'da_region'          => $request->get('shipping_region')
            ]);
        }

        if (!$customer) {
            throw new \Exception('Customer with vat_id ' . $request->get('vat_number') . ' not found and could not be created!');
        }

        if ($request->get('quote_type') === self::QUOTE_TYPE_BUY) {

            $items = [];

            //Marketino one
            $m1Id = $request->get('sconto') ? self::ITEM_M1_PROMO_WITH_DISCOUNT_ID : self::ITEM_M1_PROMO_ID;
            $m1Item = SaleItemPrice::where('short_id', '=', $m1Id)->firstOrFail();

            $notes = [
                self::SOFTWARE_PACKAGE_PRIME => QuotePackageDescription::M_PRIME,
                self::SOFTWARE_PACKAGE_HNB   => QuotePackageDescription::M_HNB
            ];

            $items[] = [
                'sale_item_price' => $m1Item,
                'quantity'        => $request->get('m1_quantity'),
                'note'            => Arr::get($notes, $request->get('software_package'), null)
            ];

            //Card reader
            if ($request->get('card_reader_quantity', 0) > 0) {
                $crItem = SaleItemPrice::where('short_id', '=', self::ITEM_CARD_READER_PROMO)->firstOrFail();

                $notes = [
                    self::CARD_READER_PLAN_STANDARD => QuotePackageDescription::CR_STANDARD,
                    self::CARD_READER_PLAN_GOLD     => QuotePackageDescription::CR_GOLD,
                    self::CARD_READER_PLAN_PLATINUM => QuotePackageDescription::CR_PLATINUM,
                ];

                $items[] = [
                    'sale_item_price' => $crItem,
                    'quantity'        => $request->get('card_reader_quantity'),
                    'note'            => Arr::get($notes, $request->get('card_reader_plan'), null)
                ];
            }

            //Setup gratis za sada je uvijek uključen
            $setupGratisItem = SaleItemPrice::where('short_id', '=', self::ITEM_SETUP_GRATIS)->firstOrFail();
            $items[] = [
                'sale_item_price' => $setupGratisItem,
                'quantity'        => 1,
            ];

            //make quote!
            $quote = (new QuoteController())->makeQuote([
                'customer_id' => $customer->id,
                //'note' => $note,
                'items'       => $items
            ]);

            //save to quote request table
            (new QuoteRequest([
                'customer_id'  => $customer->id,
                'quote_id'     => $quote->id,
                'from_web'     => true,
                'ip_address'   => $request->ip(),
                'request_data' => json_encode($request->all())
            ]))->save();

        } else if ($request->get('quote_type') === self::QUOTE_TYPE_LEASE) {

            $customer_email = $customer->email()->email;

            if (!$customer_email) {
                $url = config('web_quote.error');
                return Redirect::to($url);
            }

            Mail::to($customer_email)->send(new SendGrenke());

            //save to quote request table
            (new QuoteRequest([
                'customer_id'  => $customer->id,
                'from_web'     => true,
                'ip_address'   => $request->ip(),
                'request_data' => json_encode($request->all())
            ]))->save();

            $note = 'grenke';
            $items = [];
            $quote = (new QuoteController())->makeQuote([
                'customer_id'   =>  $customer->id,
                'note'      =>  $note,
                'items'     =>  $items,
                'data'      =>  $request->all()
            ]);

            $jobNow = CreateNextStep::dispatch([
                'customer_id'   =>  $customer->id,
                'date'          =>  \Carbon\Carbon::now(),
                'comment'       =>  'Grenke ponuda',
                'data'          =>  $request->all()
            ]);


            $url = config('web_quote.success');
            return Redirect::to($url);

        }
        $url = config('web_quote.success');
        return Redirect::to($url);
    }
}
