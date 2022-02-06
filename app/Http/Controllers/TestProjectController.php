<?php

namespace App\Http\Controllers;

use App\Minimization;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use IU\PHPCap\RedCapProject;

class TestProjectController extends Controller
{
    private $project;
    private $apiToken;
    private $apiUrl;

    function __construct()
    {
        $this->apiUrl = 'https://mslapctsd01.nottingham.ac.uk/redcap/api/';
        $this->apiToken = '95F8832D2EEAA2B9F0312D9FD1A925A3';
        $sslVerify = true;
        $this->project = new RedCapProject( $this->apiUrl, $this->apiToken, $sslVerify );

        $this->setting= '{"record_id":"record_id", "groups":["Drug1","Drug2", "Drug3","Placebo"], "distance_method":"range", "factors":{"sex":{"Female":"1","Male":"2"}, "ethnic":{"White":"1","Mixed":"2","Asian":"3","Black":"4","Other":"5"}}}';
    }

    public function index()
    {

        $projectInfo = collect( $this->project->exportProjectInfo() )
            ->only(
                'project_id',
                'project_title',
                'creation_time',
                'project_notes',
                'external_modules' );


        return view( 'project', [ 'project' => $projectInfo ] );
    }

    public function metadata()
    {
//        $forms = $this->getMetadata();

//        return view( 'metadata', [ 'forms' => $forms->groupBy( 'form_name' ) ] );
        return view( 'metadata' );

    }

    public function records()
    {
        $records = $this->getRecords();
//        ddd($records);
        return view( 'records', [ 'records' => $records ] );
    }

    public function record( $record_id )
    {
        $record = $this->getRecord( $record_id );
        $metadata = $this->getMetadata();
//        dd($record,$metadata);

        return view( 'record', [ 'record' => $record, 'metadata' => $metadata ] );
    }

    public function redcapLogin( Request $request )
    {
        $response = Http::timeout( 3 )->asForm()->acceptJson()->post( $this->apiUrl, [
            'authkey' => $request->authkey,
            'format' => 'json',
        ] );
        $redcapAuthdata = $response->body();

        if ( !$redcapAuthdata ) {
            abort( 403, 'Unauthorized.' );
        }

        $userRedcap = collect( $this->project->exportUsers() )
            ->where( 'username', json_decode( $redcapAuthdata )->username )
            ->first();

        $user = DB::transaction( function () use ( $userRedcap ) {
            return tap( User::updateOrCreate(
                [ 'email' => $userRedcap[ 'email' ] ],
                [ 'name' => $userRedcap[ 'firstname' ] . ( $userRedcap[ 'lastname' ] ? ' ' . $userRedcap[ 'lastname' ] : '' ),
                    'redcap' => 1
                ]
            ), function ( User $user ) {
                $user->ownedTeams()->save( Team::unguarded( function () use ( $user ) {
                    return Team::firstOrCreate(
                        [ 'user_id' => $user->id, 'personal_team' => true, ],
                        [ 'name' => explode( ' ', $user->name, 2 )[ 0 ] . "'s Team" ] );
                } ) );
            } );
        } );


        $request->session()->put( 'redcapLoggedIn', Crypt::encryptString( $redcapAuthdata ) );

        Auth::login( $user );


        return redirect()->route( 'redcapLogged' );
    }

    public function redcapLogged( Request $request )
    {
        if ( !$request->session()->has( 'redcapLoggedIn' ) ) {
            abort( 403, 'Unauthorized.' );
        }

        $redcapLoggedIn = json_decode( Crypt::decryptString( session( 'redcapLoggedIn' ) ) );

        $user = collect( $this->project->exportUsers() )
            ->where( 'username', $redcapLoggedIn->username )
            ->first();

        $fields = [
            'username' => 'Username',
            'email' => 'Email',
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'expiration' => 'expiration',
            'data_access_group' => 'Data access group' ];
        return view( 'redcapLoggedIn', [ 'user' => $user, 'fields' => $fields ] );
    }

    public function randomise( $record_id )
    {
        $minim = new Minimization( $this->setting );

        $records = $this->getRecords();


        list($group, $imbalance_score) = $minim->enroll( $record_id, $records );

        $time=now();

        $data[0]=[
            'record_id' => $record_id,
            'rand_group' => $group,
            'rand_time' => $time,
            'baseline_data_complete' => '2'
        ];


        $test = $this->project->importRecords($data);

        if($test){
            return redirect()
                ->route( 'record', ['record_id' => $record_id] )
                ->with('status', "Participants with id '$record_id' were successfully randomized to group '$group' on $time")
                ->with('minim', ['minimisation_table' => $minim->getMiniTable(), 'freq_table' => $minim->getFreqTable(), 'imbalance_score' => $imbalance_score]);
        }
    }

    public function minimisation(  )
    {
        $minim = new Minimization( $this->setting );

        $records = $this->getRecords()->groupBy( [ 'rand_group', 'record_id' ] );

        $minim->buildMiniTable( $records );

//        dd($minim->getFreqTable(), $minim->getMiniTable());
//        $minim->getFactors();

        return view( 'minimisationInfo', [ 'minim' => $minim] );



    }

    public function test_randoms($rollsNumber=5000000)
    {
        $randoms = ['rand', 'mt_rand', 'random_int',];
        $groupsNumber = 10;

        $randoms_result = [];

        foreach ($randoms as $random){
            $randoms_result[$random] = $this->testRand($random, $groupsNumber, $rollsNumber);
        }
        return view( 'random_test', [ 'results' => $randoms_result] );
    }

    private function testRand($randFunction, $groupsNumber = 10, $rollsNumber = 210)
    {
        $frequencies = array_fill(0, $groupsNumber, 0);
        $start = microtime(true);
        foreach (range(1, $rollsNumber) as $ignored) {
            $frequencies[$randFunction(0, $groupsNumber - 1)]++;
        }
        $time_elapsed_secs = microtime(true) - $start;

        return [$frequencies, $time_elapsed_secs, $rollsNumber ];
    }

    public function getMetadata()
    {
        $metadata = collect( $this->project->exportMetadata() )
            ->mapWithKeys( function ( $item ) {
                $field_with_choices = [ 'checkbox', 'radio', 'dropdown' ];
                // convert the choices to array
                if ( in_array( $item[ 'field_type' ], $field_with_choices )
                    && $item[ 'select_choices_or_calculations' ] ) {
                    $item[ 'select_choices_or_calculations' ] =
                        $this->splitChoices( $item[ 'select_choices_or_calculations' ] );
                }

                return [ $item[ 'field_name' ] => $item ];
            } );
        return ( $metadata );
    }

    private function splitChoices( $choices )
    {
        $choices = explode( ' | ', $choices );

        $list_choice = [];
        foreach ( $choices as $choice ) {
            list( $key, $value ) = preg_split( "/, /", $choice, 2 );
            $list_choice[ $key ] = $value;
        }
        return $list_choice;
    }

    private function getRecord( string $record_id = null )
    {
        return collect( $this->project->exportRecords( 'php', 'flat', [$record_id] ) )
            ->mapWithKeys( function ( $item ) {
                $data = [];

                foreach ( $item as $key => $value ) {
                    $fields = preg_split( "/___/", $key, 2 );
                    if ( sizeof( $fields ) === 2 ) {
                        $data[ $fields[ 0 ] ] [ $fields[ 1 ] ] = $value;
                    } else {
                        $data[ $fields[ 0 ] ] = $value;
                    }
                }
                return [ $item[ 'record_id' ] => $data ];
            } )
            ->first();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getRecords(): \Illuminate\Support\Collection
    {
        return collect( $this->project->exportRecords() );
    }

}
