<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\RedcapLaravel;
use Illuminate\Http\Request;
use IU\PHPCap\RedCapProject;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();

        return view( 'project.index', [ 'projects' => $projects ] );

    }

    public function show( Project $project )
    {


        return view( 'project.show', [ 'project' => $project ] );

    }

    public function create()
    {

        return view( 'project.create' );

    }



}
