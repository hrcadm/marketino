<?php

namespace App\View\Components;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\Component;

class ShowCustomerComponent extends Component
{
    /**
     * @var Customer|Customer[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public $customer;
    /**
     * @var \Illuminate\Routing\Route|object|string|null
     */
    public $tab;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {

        $this->customer = $request->route('customer');
        $this->tab = $request->route('tab', 'details');
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.show-customer-component', [
            'customer' => $this->customer
        ]);
    }
}
