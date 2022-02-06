<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class errorMessage extends Component
{
    public $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.error-message');
    }
}
