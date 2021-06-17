<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\SaleItemPrice;
use App\Models\User;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function quotesIndex()
    {
        return view('pages.billing.quotes');
    }
}
