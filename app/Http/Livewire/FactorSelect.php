<?php

namespace App\Http\Livewire;

use Asantibanez\LivewireSelect\LivewireSelect;
use Illuminate\Support\Collection;

class FactorSelect extends LivewireSelect
{
    public function options($searchTerm = null) :Collection
    {
        return collect([
            [
                'value' => 'honda',
                'description' => 'Honda',
            ],
            [
                'value' => 'mazda',
                'description' => 'Mazda',
            ],
            [
                'value' => 'tesla',
                'description' => 'Tesla',
            ],
        ]);
    }
}
