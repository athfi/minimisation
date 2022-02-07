<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use IU\PHPCap\PhpCapException;
use IU\PHPCap\RedCapProject;
use Livewire\Component;
use PharIo\Version\Exception;

class CreateProjectForm extends Component
{
    public $url;
    public $name;
    public $token;
    public $error;

    protected $rules = [
        'name' => 'required',
        'url' => 'required|url',
        'token' => 'required|alpha_num|max:32|min:32'
    ];

    protected $validationAttributes = [
        'name' => 'project name',
        'url' => 'REDCap url',
        'token' => 'REDCap token',
    ];


    public function render()
    {
        return view( 'project.create-project-form' );
    }

    public function updated( $propertyName )
    {
        $this->validateOnly( $propertyName );
    }

    public function createProject()
    {
        $this->validate();


        $apiUrl = $this->url;
        $apiToken = $this->token;
        $sslVerify = true;

        $errors = $this->getErrorBag();

        try {
            $project = new RedCapProject( $apiUrl, $apiToken, $sslVerify );
            //try to connect
            $project->exportProjectInfo();

        } catch (PhpCapException $e) {

//            if ( App::environment( 'local' ) ) {
//                throw $e;
//            }
            $eMessage = "Connection test to REDCap server failed. ";

            $errors->add( 'connection', $eMessage . $e->getMessage() );

            Log::error( $e );
        }

        // Give default value
        $group = [
            [ 'id' => (string)Str::uuid(),
                'name' => 'Drug 1',
                'ratio' => '1'
            ],
            [ 'id' => (string)Str::uuid(),
                'name' => 'Drug 2',
                'ratio' => '1'
            ],
            [ 'id' => (string)Str::uuid(),
                'name' => 'Drug 3',
                'ratio' => '1'
            ],
            [ 'id' => (string)Str::uuid(),
                'name' => 'Placebo',
                'ratio' => '1'
            ],
        ];

        // Give default value
        $factor = [
            [
                'id' => (string)Str::uuid(),
                'name' => 'Factor 1',
                'weight' => '1',
                'type' => 'Radio',
                'config' => [
                    'fieldName' => ''
                ]
            ],
            [
                'id' => (string)Str::uuid(),
                'name' => 'Factor 2',
                'weight' => '1',
                'type' => 'Calculated',
                'config' => [
                    [
                        'id' => (string)Str::uuid(),
                        'priority' => 1,
                        'name' => 'Level 1',
                        'formula' => 'age<3'
                    ],
                    [
                        'id' => (string)Str::uuid(),
                        'priority' => 2,
                        'name' => 'Level 2',
                        'formula' => 'age<15'
                    ],
                ]
            ]
        ];



        if ( $errors->isEmpty() or true) {
            $newProject = Project::create( [
                'name' => $this->name,
                'url' => $this->url,
                'token' => $this->token,
                'owner_id' => Auth::id(),
            ] );

            $newProject->fill( [
                'setting->record_id' => 'record_id',
                'setting->randGroup' => 'rand_group',
                'setting->randTime' => 'rand_time',
                'setting->group' => $group,
                'setting->factor' => $factor,
            ] );

            $newProject->save();


            return redirect()->route( 'project-show', [ 'project' => $newProject ] );
        }

    }
}
