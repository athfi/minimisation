<?php

namespace App\Http\Livewire\Project;

use App\RedcapLaravel;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use IU\PHPCap\RedCapProject;
use Livewire\Component;

class ShowProjectForm extends Component
{
    public $project;
    public $projectInfo;
    public $projectMetadata;
    public $projectSetting;

    public $readyToLoad = false;


    public function loadData()
    {
        $this->projectMetadata = [];
        $this->projectInfo = [];

        $sslVerify = true;
        $redcapProject = new RedcapLaravel( $this->project, $sslVerify );

        try {
            $projectInfo = $redcapProject->exportProjectInfo();
            $this->projectInfo = [
                'ID' => $projectInfo[ 'project_id' ],
                'Title' => $projectInfo[ 'project_title' ],
                'Created at' => $projectInfo[ 'creation_time' ],
                'Notes' => $projectInfo[ 'project_notes' ],
                'External Modules' => preg_replace( '/,/', ', ', $projectInfo[ 'external_modules' ] )
            ];

            $this->projectMetadata = $redcapProject->getMetadata()
                ->groupBy( 'form_name' );

        } catch (\Exception $e) {
            if ( App::environment( 'local' ) ) {
                throw $e;
            }

            Log::error( $e );
            $message = $e->getMessage();
            session()->flash( 'error', "Failed to get data from REDCap. $message" );

        }

        $this->readyToLoad = true;

    }

    public function render()
    {
        $this->projectSetting = [
            'Project Name' => $this->project->name,
            'Project url' => $this->project->url,
            'Project token' => $this->project->token
        ];

        return view( 'livewire.project.show-project-form', [
            'metadata' => $this->projectMetadata,
            'projectIndo' => $this->projectMetadata ] );

        return view( 'livewire.project.show-project-form' );
    }


}
