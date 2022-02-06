<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\RedcapLaravel;
use Illuminate\Http\Request;

class MyProjectController extends Controller
{

    public function redcapAPI()
    {
        $this->apiUrl = 'https://mslapctsd01.nottingham.ac.uk/redcap/api/';
        $this->apiToken = '95F8832D2EEAA2B9F0312D9FD1A925A3';
        $sslVerify = true;
        $this->project = new RedCapProject( $this->apiUrl, $this->apiToken, $sslVerify );

        $this->setting= '{"record_id":"record_id", "groups":["Drug1","Drug2", "Drug3","Placebo"], "distance_method":"range", "factors":{"sex":{"Female":"1","Male":"2"}, "ethnic":{"White":"1","Mixed":"2","Asian":"3","Black":"4","Other":"5"}}}';
    }

    public function records( Project $project )
    {
        $redcap = new RedcapLaravel($project);

        $recordId = $redcap->getRecordID();

        $records = $redcap->getRecords();

        $field = [
            'recordId' => $recordId,
            'randGroup' => $project->setting['randGroup'],
            'randTime' => $project->setting['randTime'],
        ];

        return view( 'records', [ 'projectId' =>$project->id, 'records' => $records, 'field' => $field ] );
    }

    public function record(Project $project, $recordId )
    {
        $redcap = new RedcapLaravel($project);

        $record = $redcap->getRecord( $recordId );

        $metadata = $redcap->getMetadata();


        return view( 'record', [ 'record' => $record, 'metadata' => $metadata ] );
    }

    public function minimisationSetting(Project $project)
    {

        return view( 'project.minimisation-setting', ['project' => $project] );

    }

}
