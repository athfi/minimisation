<?php

namespace App\Http\Livewire;

use App\Http\Controllers\TestProjectController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Metadata extends Component
{
    private $metadata ;

    public $readyToLoad = false;


    public function loadMetadata()
    {
        $project = new TestProjectController();

        try {
            $this->metadata = $project->getMetadata()->groupBy( 'form_name' );

        } catch (\Exception $e){
//            if (App::environment('local')) {
//                throw $e;
//            }

            Log::error($e);
            $message = $e->getMessage();
            session()->flash('error', "Failed to get metadata from REDCap. $message");

        }

        $this->readyToLoad = true;

    }


    public function render()
    {
        return view('livewire.metadata', [
            'metadata' => $this->readyToLoad
                ? $this->metadata
                : [],
        ]);
    }


}
