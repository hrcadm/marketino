<?php

namespace App\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class Navigation extends Component
{
    /**
     * The component's listeners.
     *
     * @var array
     */
    protected $listeners = [
        'refresh-navigation-dropdown' => '$refresh',
    ];

    /**
     * Render the component.
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.navigation');
    }
}
