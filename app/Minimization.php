<?php

namespace App;

use Exception;
use Hamcrest\Thingy;
use Illuminate\Support\Collection;

class Minimization
{
    private $groups;
    private $factors;
    private $freq_table;
    private $mini_table;


    function __construct( string $setting, array $minim_table = NULL )
    {
        $setting = collect(json_decode( $setting ))->recursive();
        $this->groups = $setting['groups'];
        $this->factors = $setting['factors'];
        $this->distance_method = $setting['distance_method'];
        $this->setMiniTable($minim_table);
        $this->setFreqTable();
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function getFactors()
    {
        return $this->factors;
    }

    public function getFreqTable()
    {
        return $this->freq_table;
    }

    public function getMiniTable(): array
    {
        return $this->mini_table;
    }


    public function enroll( $record_id, $records )
    {
        $new_participant = $records
            ->where( 'record_id', '==', $record_id )
            ->first();

        throw_if( $new_participant[ 'rand_group' ] != '',
            new \Exception( 'Participant already randomised.' ) );

        $this->buildMiniTable( $records->groupBy( [ 'rand_group', 'record_id' ] ) );

        $allocation = $this->getGroup( $new_participant );

        return $allocation;
    }

    public function buildMiniTable( Collection $records )
    {
        $temp_mini_table = [];
        $freq_table = $this->createFreqTable();
        foreach ($this->groups as $group){
            $temp_mini_table[$group]=[];
            if ($records->has($group)){
                foreach ($records[$group] as $id => $data){
                    $data = $data->first();
                    foreach ($this->factors as $factor=>$level){
                        throw_if(! isset($data[$factor]), new Exception('Error'));
                        $temp_mini_table[$group][$id][$factor]=$data[$factor];
                    }
                }
            }
        }

        $this->mini_table = $temp_mini_table;
        $this->freq_table = $this->buildFreqTable($temp_mini_table, $freq_table);
    }

    private function createFreqTable(): array
    {
        $freq_table = [];
        foreach ( $this->groups as $group ) {
            foreach ( $this->factors as $factor => $levels ) {
                foreach ( $levels as $level => $criteria ) {
                    $freq_table[ $group ][ $factor ][ $level ] = 0;
                }
            }
        }
        return $freq_table;
    }

    private function getGroup( $new_participant )
    {
        $new_factors = $this->getNewFactors( $new_participant );
        $temp_freq = $this->getTempFreq( $new_factors );

        //find imballance score for each group
        $imbalance_score = [];
        foreach ($temp_freq as $group => $factors){
            $score=0;
            foreach ($factors as $freqs){
                $score += $this->range_distance($freqs);
            }
            $imbalance_score[$group] = $score;
        }

        $min_score = min($imbalance_score);
        //groups that have value = min imbalance
        $min_list = [];
        foreach ($imbalance_score as $group => $value){
            if ($value == $min_score){
                $min_list[]=$group;
            }
        }

        //TODO Get probability of each group

        //TODO get group based on probablility
        $new_group = $this->getNewGroup( $min_list );

        array_push($this->mini_table[$new_group], $new_factors );
        $this->buildFreqTable();

        return [$new_group, $imbalance_score];
    }

    private function setFreqTable()
    {
        $freq_table = $this->createFreqTable();

        if ( count( $this->mini_table ) > 0 ) {
            $this->freq_table = $this->buildFreqTable($this->mini_table, $freq_table );
        }

    }


    private function buildFreqTable( array $mini_table = NULL, $freq_table = NULL )
    {
        $mini_table = $mini_table ?? $this->mini_table;
        $freq_table = $freq_table ?? $this->freq_table;
            foreach ($this->groups as $group){
                if (isset($mini_table[$group]) && count($mini_table[$group])){
                    $freq_table = $this->newFreqData($mini_table[$group], $group, $freq_table);
                }
            }

            return $freq_table;
    }




    private function getLevel( string $factor, $value )
    {
        throw_if( ! $this->factors->has($factor),
            new Exception( "Invalid factor name. '$factor' can't be found in minimisation setting." ) );
//        dd($this->factors, $factor, $value);
        foreach ( $this->factors[ $factor ] as $level => $test_val ) {
            if ( $value == $test_val ) {
                return $level;
            }
        }

        throw new Exception( "Can't find level for '$factor' factor with value = '$value'." );
    }

    private function newFreqData( array $new_mini, $group, $freq_table = NULL  )
    {

        $freq_table = $freq_table ?? $this->freq_table;
        foreach ( $new_mini as $id => $factors ) {
            foreach ($factors as $factor => $value){
                $level = $this->getLevel( $factor, $value );
                $freq_table[$group][ $factor ][ $level ] += 1;
            }
        }

        return $freq_table;
    }


    private function getNewFactors( $data ): array
    {
        $new_factors = [];
        foreach ( $this->factors as $factor => $level ) {
            throw_if( !isset( $data[ $factor ] ),
                new Exception( "Can't find '$factor' factor in New participant data." ) );

            $new_factors[ $factor ] = $data[ $factor ];
        }
        return $new_factors;
    }



    /**
     * @param array $new_factors
     * @return array
     */
    private function getTempFreq( $new_factors ): array
    {
        $temp_freq = [];

        $factors = $this->factors->toArray();
        foreach ( $factors as $factor => $l ) {
            $level= array_search ($new_factors[$factor], $factors[$factor]);
            $groups = $this->groups->toArray();
            foreach ( $groups as $group ) {
                foreach ( $groups as $g ) {
                    $freq = $this->freq_table[$g][$factor][$level];
                    if ($group == $g ){
                        $freq ++;
                    }
                    $temp_freq[$group][$factor][$g] = $freq;
                }
            }
        }

        return $temp_freq;
    }

    private function setMiniTable( ?array $minim_table )
    {
        if (is_null( $minim_table)){
            $minim_table =[];
            foreach ($this->groups as $group){
                $minim_table[$group]=[];
            }
        }

        $this->mini_table = $minim_table;
    }



    private function range_distance(array $freqs ) : int
    {
        return max($freqs) - min($freqs);
    }

    /**
     * @param $min_list
     * @return mixed
     */
    private function getNewGroup( $min_list )
    {
        $new_group = $min_list[ array_rand( $min_list, 1 ) ] ;
        return $new_group;
    }
}
