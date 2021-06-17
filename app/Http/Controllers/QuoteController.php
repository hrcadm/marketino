<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteItemRequest;
use App\Mail\SendGrenke;
use App\Mail\SendQuote;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\QuoteRequest;
use App\Models\SaleItemPrice;
use App\Models\NextStep;
use App\Utils\PdfQuote;
use Arr;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Jobs\CreateNextStep;

class QuoteController extends Controller {

    public function newQuote(QuoteItemRequest $request, string $customer_id) {

        $marketinoOne = $request->get('marketinoOne');
        $marketinoOneLimited = $request->get('marketinoOneLimited');
        $marketinoOneRate = $request->get('marketinoOneRate');
        $cardReader = $request->get('cardReader');
        $shipment = $request->get('shipment');
        $setup = $request->get('setup');

        $marketinoOneNote = $request->get('marketinoOneNote');
        $marketinoOneRateNote = $request->get('marketinoOneRateNote');
        $cardReaderNote = $request->get('cardReaderNote');

        $quoteItems = [];

        foreach ($marketinoOne as $key => $val) {
            $quoteItems[] = [
                'sale_item_price' => SaleItemPrice::where('short_id', $key)->firstOrFail(),
                'quantity'        => $val,
                'note'            => $marketinoOneNote//#drazen/todo::prokomentirati kamo i kome slati notu 'H&B | PRIME'
            ];
        }

        foreach ($marketinoOneLimited as $key => $val) {
            $quoteItems[] = [
                'sale_item_price' => SaleItemPrice::where('short_id', $key)->firstOrFail(),
                'quantity'        => $val,
                'note'            => $marketinoOneNote
            ];
        }

        // dd($quoteItems);

        foreach ($marketinoOneRate as $key => $val) {
            $quoteItems[] = [
                'sale_item_price' => SaleItemPrice::where('short_id', $key)->firstOrFail(),
                'quantity'        => $val,
                'note'            => $marketinoOneRateNote
            ];
        }


        foreach ($cardReader as $key => $val) {
            $quoteItems[] = [
                'sale_item_price' => SaleItemPrice::where('short_id', $key)->firstOrFail(),
                'quantity'        => $val,
                'note'            => $cardReaderNote
            ];
        }

        if ($shipment) {
            foreach ($shipment as $key => $val) {
                if ($val && $key !== 'note') {
                    $quoteItems[] = [
                        'sale_item_price' => SaleItemPrice::where('short_id', $key)->firstOrFail(),
                        'quantity'        => $val,
                    ];
                }
            }
        }

        if ($setup) {
            foreach ($setup as $key => $val) {
                $quoteItems[] = [
                    'sale_item_price' => SaleItemPrice::where('short_id', $key)->firstOrFail(),
                    'quantity'        => $val,
                ];
            }
        }

        if (count($quoteItems) > 0) {
            $quote = $this->makeQuote([
                'customer_id' => $customer_id,
                'note'        => '',
                'items'       => $quoteItems
            ]);

            //save to quote request table
            $quoteRequest = (new QuoteRequest([
                'customer_id'  => $customer_id,
                'quote_id'     => $quote->id,
                'from_web'     => false,
                'user_id'      => \Auth::id(),
                'request_data' => json_encode($request->all())
            ]))->save();
            return Redirect::back()->with('message', 'Quote sent!');
        }

        return Redirect::back()->withErrors('message', 'Quote did not created!');
    }

    public function makeQuote(array $array): Quote {

        $customer = Customer::findOrFail(Arr::get($array, 'customer_id'));
        $note = Arr::get($array, 'note', '');
        $items = Arr::get($array, 'items', []);
        $quoteItems = new Collection();
        // $fromWeb = Arr::get($array,'from_web',false);
        foreach ($items as $item) {

            $quantity = (int)Arr::get($item, 'quantity', 0);
            if (!$quantity) {
                continue;
            }

            $saleItemPrice = Arr::get($item, 'sale_item_price');
            $note = Arr::get($item, 'note', '');
            $quoteItems->add(QuoteItem::makeItem($saleItemPrice, $quantity, $note));
        }

        $quote = Quote::makeQuote($customer, $quoteItems, $note);
        $quote->save();
        $quote->items()->saveMany($quoteItems);

        if($quote->user_id) {
            $jobAfter3Day = CreateNextStep::dispatch([
                'customer_id'   =>  $customer->id,
                'user_id'       =>  $quote->user_id,
                'quote_id'      =>  $quote->id,
                'date'          =>  \Carbon\Carbon::now()->addDays(3),
                'comment'       =>  'Ponuda'
            ])->delay(now()->addMinutes(1));

            $jobAfter7Day = CreateNextStep::dispatch([
                'customer_id'   =>  $customer->id,
                'user_id'       =>  $quote->user_id,
                'quote_id'      =>  $quote->id,
                'date'          =>  \Carbon\Carbon::now()->addDays(7),
                'comment'       =>  'Ponuda'
            ])->delay(now()->addDays(7));
        } else {
            // Web qoute request
            $jobNow = CreateNextStep::dispatch([
                'customer_id'   =>  $customer->id,
                'quote_id'      =>  $quote->id,
                'date'          =>  \Carbon\Carbon::now(),
                'comment'       =>  'Web ponuda'
            ]);
        }



        //reload all database ids and auto increments
        $quote = $quote->fresh();

        //generate pdf
        $tempPdfPath = $this->generatePdf($quote);

        //upload to cloud storage
        Storage::cloud()->put($quote->getPdfPath(), file_get_contents($tempPdfPath));

        //send to mail
        $this->sendQuoteToMail($quote, null, $tempPdfPath);

        return $quote;
    }


    private function generatePdf(Quote $quote) {
        $pdf = PDF::loadView('pdf.quote', [
            'quote'    => $quote,
            'pdfQuote' => new PdfQuote($quote)
        ])->setPaper('a4');

        return $this->saveLocalPdf($pdf->output());
    }

    private function saveLocalPdf(string $content): string {
        $tempPdf = tempnam(sys_get_temp_dir(), 'hubquote_');
        file_put_contents($tempPdf, $content);

        return $tempPdf;
    }

    public function sendQuoteToMail(Quote $quote, string $email = null, string $localPdfPath = null) {

        $email = $email ?? $this->getCustomerEmail($quote);
        if (!$email) {
            return;
        }
        $attachment = $localPdfPath ?? $this->getLocalPdfPath($quote);

        Mail::to($email)->send(new SendQuote($quote, $attachment));
    }

    private function getCustomerEmail(Quote $quote) {

        $email = $quote->customer->emailAddress;

        if (!$email) {
            throw new Exception('Can not find customer email');
        }

        return $email;
    }

    private function getLocalPdfPath(Quote $quote) {

        return $this->saveLocalPdf(Storage::cloud()->get($quote->getPdfPath()));
    }


    public function sendToEmail(string $quote_id) {

        $quote = Quote::findOrFail($quote_id);
        $customerEmail = $this->getCustomerEmail($quote);
        if (!$customerEmail) {
            return redirect()->back()->withInput()->withErrors([
                'email' => "Customer has no email"
            ]);
        }

        $this->sendQuoteToMail($quote, $customerEmail);

        return Redirect::back()->with('message', "Quote sent to $customerEmail!");

    }

    public function downloadPdf(string $quote_id) {

        $quote = Quote::findOrFail($quote_id);


        // if(! Storage::cloud()->exists($quote->getPdfPath())) {
        //     //generate pdf
        //     $tempPdfPath = $this->generatePdf($quote);

        //     //upload to cloud storage
        //     Storage::cloud()->put($quote->getPdfPath(), file_get_contents($tempPdfPath));
        // }

        return Storage::cloud()->download($quote->getPdfPath(), $quote->getPdfFileName(), ['Content-type' => 'application/pdf']);
    }

    public function sendGrenkeEmail(Request $request, $customer = null) {
        if($customer) {
            $customer = Customer::findOrFail($customer);
            $customer_email = $customer->email()->email;

            $note = 'grenke';
            $quoteItems = [];
            $quote = $this->makeQuote([
                'customer_id' => $customer->id,
                'note'        => $note,
                'items'       => $quoteItems
            ]);



        }
        if($request->customer_email) {
            $customer_email = $request->customer_email;
        }
        if (!$customer_email) {
            return redirect()->back()->withInput()->withErrors([
                'email' => "Customer has no email"
            ]);
        }

        Mail::to($customer_email)->send(new SendGrenke());
        return Redirect::back()->with('message', "Grenke mail sent to ".$customer_email );
    }
}
