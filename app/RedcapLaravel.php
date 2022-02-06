<?php

namespace App;

use App\Models\Project;
use IU\PHPCap\RedCapProject;

class RedcapLaravel extends RedCapProject
{
    public $recordId;

    public function __construct(
        Project $project,
        $sslVerify = false,
        $caCertificateFile = null,
        $errorHandler = null,
        $connection = null
    ){
        $apiUrl = $project->url;
        $superToken = $project->token;
        parent::__construct($apiUrl, $superToken, $sslVerify, $caCertificateFile, $errorHandler, $connection);

        $this->recordId = $this->getRecordID();

    }

    public function getMetadata()
    {
        $metadata = collect( $this->exportMetadata() )
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

    public function getRecords(): \Illuminate\Support\Collection
    {
        return collect( $this->exportRecords() );
    }

    public function getRecordID()
    {
        return array_key_first( $this->getMetadata()->toArray());
    }

    public function getRecord( string $record_id = null )
    {
        return collect( $this->exportRecords( 'php', 'flat', [$record_id] ) )
            ->mapWithKeys( $this->SplitMultiChoices() )
            ->first();
    }

    /**
     * @return \Closure
     */
    private function SplitMultiChoices(): \Closure
    {
        return function ( $item ) {
            $data = [];

            foreach ( $item as $key => $value ) {
                $fields = preg_split( "/___/", $key, 2 );
                if ( sizeof( $fields ) === 2 ) {
                    $data[ $fields[ 0 ] ] [ $fields[ 1 ] ] = $value;
                } else {
                    $data[ $fields[ 0 ] ] = $value;
                }
            }
            return [ $item[ $this->recordId ] => $data ];
        };
    }
}
