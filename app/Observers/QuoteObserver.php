<?php

namespace App\Observers;

use App\Models\Quote;
use Carbon\Carbon;
use RuntimeException;

class QuoteObserver
{

    public function creating(Quote $quote)
    {
        //TODO: set "working" date (no weekend and hollydays)
        $quote->valid_until = Carbon::today()->addDays(7);
    }

    /**
     * Handle the Quote "created" event.
     *
     * @param  \App\Models\Quote  $quote
     * @return void
     */
    public function created(Quote $quote)
    {
        //generate and save control_number
        $quote = $quote->fresh();//reload from db to get autoincrement number
        if (!$quote) {
            throw new \RuntimeException('Quote not saved in DB so can not generate control number');
        }
        $quote->control_number = $quote->calculateControlNumber();
        $quote->saveQuietly();
    }
}
