<?php

namespace App\Http\Livewire\Billing\Quotes;

use App\Enum\QuoteStatus;
use App\Models\Invoice;
use App\Models\Quote;
use Livewire\Component;

class QuoteTd extends Component
{
    /* @var Quote $quote */
    public Quote $quote;

    /* @var boolean $paymentModal */
    public bool $paymentModal = false;

    /* @var boolean $packagesModal */
    public bool $packagesModal = false;

    public $cro, $invoiceNo;

    public function mount()
    {
        $this->fill([
            'cro' => ($this->quote->invoice()->exists()) ? $this->quote->invoice->cro : null,
            'invoiceNo' => ($this->quote->invoice()->exists()) ? $this->quote->invoice->invoice_number : null
        ]);
    }

    public function render()
    {
        return view('livewire.billing.quotes.quote-td');
    }

    public function updatePayment()
    {
        ($this->quote->invoice()->exists()) ?
            $this->quote->invoice()->update([
                'cro' => $this->cro,
                'invoice_number' => $this->invoiceNo
            ]) :
            $this->quote->invoice()->insert([
                'cro' => $this->cro,
                'invoice_number' => $this->invoiceNo
            ]);

        $this->quote->status = QuoteStatus::PAID();
        $this->quote->save();

        $this->paymentModal = false;

        // dispatch global event to show notification system wide
        $this->dispatchBrowserEvent('notification', ['type' => 'success', 'text' => 'Povezivanje uplate uspješno!']);
    }
}
