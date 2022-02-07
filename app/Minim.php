<?php

namespace App;

class Minim

{
    public $groups;
    public $vars;
    public $freqTable;
    public $miniTable = [];
    public $RandPercentage;

    function __construct()
    {
        $this->groups = ["IMP", "P"];
        $this->vars = [
            'var1' => [
                'level1' => '<4',
                'level2' => '=>4 and <10'
            ],
            'var2' => [],
            'var3' => []
        ];
        $this->RandPercentage = 5; //5% flip

        $this->miniTable = [];
        $this->freqTable = [];

        foreach ($this->groups as $g) {
            foreach ($this->vars as $v) {
                $this->freqTable[$g][$v] = 0;
            }
        }

        //dfraft
        $this->hospital_repatriation_value =[
            "hospital1" => 60,
            "hospital2" => 70,
            "hospital3" => 30,
            "hospital4" => 85,
            "hospital5" => 60,
            "hospital6" => 60,
            "hospital7" => 60
        ];
    }

    private function getFactorMap($infoRandomisation)
    {
        $infoRandomisation = (array)$infoRandomisation;

        // Get values from the randomisation record.
        $dateRandomised = new DateTime($infoRandomisation['time_randomised_r']);
        $dateOnset = new DateTime($infoRandomisation['time_onset_r']);
        $dateOfBirth = new DateTime($infoRandomisation['date_of_birth_r']);
        $nihssScore = $infoRandomisation['nihss_total_r'];
        $mrsScore = $infoRandomisation['mrs_r'];
        $repatriationValues = $this->getRepatiationValues($infoRandomisation['hospital_r']);
        $treatment = $infoRandomisation['treatment_r'];
//        $isFlip = $infoRandomisation['r_isFlip'];
        $isFlip = false;

        // Calculate the participant's age at randomisation.
        $age = $dateRandomised->diff($dateOfBirth)->y;

        $diffOnset = $dateRandomised->diff($dateOnset);
        $timeOnset = $diffOnset->h + ($diffOnset->i) / 60;

        $factor_map = [
            'participant_id' => $infoRandomisation['participant_id'],
            'nihss10_15' => ($nihssScore >= 10 and $nihssScore <= 10),
            'nihss16_20' => ($nihssScore >= 16 and $nihssScore <= 20),
            'nihss20_25' => ($nihssScore >= 20 and $nihssScore <= 25),
            'nihssM25' => ($nihssScore > 25),
            'ageL60' => ($age < 60),
            'age60_80' => ($age > 60 and $age <= 60),
            'ageM80' => ($age > 80),
            'mrs0_2' => ($mrsScore <= 2),
            'mrsM2' => ($mrsScore > 2),
            'onsetL4' => $timeOnset < 4,
            'onset4_6' => $timeOnset >= 4 and $timeOnset <= 6,
            'onsetM6' => $timeOnset > 6,
            'repatrL15' => $repatriationValues < 15,
            'repatr15L70' => $repatriationValues >= 15 and $repatriationValues < 70,
            'repart70up' => $repatriationValues >= 70,
            'treatment' => $treatment,
            'isFlip' => $isFlip,
            'age' => $age

        ];

        return $factor_map;
    }


    public function assign_participant($listInfoRandomisation, $newParticipantID)
    {

        $this->buildTable($listInfoRandomisation);

        $result = $this->getGroup($listInfoRandomisation, $newParticipantID);

        return $result;
    }

    public function buildTable($listInfoRandomisation)
    {
        //sort list by time randomisation
        $list_time_randomised = array_column($listInfoRandomisation, 'time_randomised_r');

        //if there is randomisation, sort using rand date
        if ($list_time_randomised){
            array_multisort ($list_time_randomised,
                SORT_ASC, $listInfoRandomisation);

            foreach ($listInfoRandomisation as $InfoRandomisation) {
//            dd($InfoRandomisation);
                $treatment = $InfoRandomisation->treatment_r;
//            if treatment is valid, update the freq table and miniTable

                if (in_array($treatment, $this->groups)) {
                    $this->updateMiniTables($InfoRandomisation);
                }
            }
        }


    }

    private function updateMiniTables($InfoRandomisation)
    {
        $newFactor = $this->getFactorMap($InfoRandomisation);
        array_push($this->miniTable, $newFactor);

        //update freq table
        $treatment = $newFactor['treatment'];

        foreach ($newFactor as $key => $value) {
            if (array_key_exists($key, $this->freqTable[$treatment])) {
                $this->freqTable[$treatment][$key] += $value;
            }
        }

    }


    private function getGroup($listInfoRandomisation, $newParticipantID)
    {
        $list_participants = array_column($listInfoRandomisation, 'participant_id');

        if (in_array($newParticipantID, $list_participants)) {
            $key = array_search($newParticipantID, $list_participants);
            $InfoRandomisation = $listInfoRandomisation[$key];

            $newFactor = $this->getFactorMap($InfoRandomisation);

            $tempFreqTable = [];
            $temImbalance = [];
            $tempMininImbalance = INF;
            //    calculate and get min imbalance value
            foreach ($this->groups as $g) {
                $tempFreqTable[$g] = $this->createTempFreqTable($newFactor, $g);
                $temImbalance[$g] = $this->calculateImbalance($tempFreqTable[$g]);
                if ($temImbalance[$g] < $tempMininImbalance) {
                    $tempMininImbalance = $temImbalance[$g];
                    $miniGroups = [$g];
                } elseif ($temImbalance[$g] = $tempMininImbalance) {
                    array_push($miniGroups, $g);
                }
            }

            //get group to be assigned, choose randomly if more than one group has the same imbalance value
            $group = $miniGroups[array_rand($miniGroups, 1)];

            $flip = false;

            if (mt_rand(1, 100) <= $this->RandPercentage) {
                $flipGroups = $this->groups;
                // remove the group from flipsGroups
                if (($key = array_search($group, $flipGroups)) !== false) { // if key exist
                    unset($flipGroups[$key]);
                }
                $group = $miniGroups[array_rand($flipGroups, 1)];
                $flip = True;
            }


            $this->freqTable = $tempFreqTable[$group];
            $newFactor['treatment'] = $group;
            $newFactor['isFlip'] = $flip;
            array_push($this->miniTable, $newFactor);

            $result['group'] = $group;
            $result['isFlip'] = $flip;
            $result['age'] = $newFactor['age'];
            $result['time_randomisation'] = date("Y-m-d H:i:s");

            return $result;

        } else {
            //if participant not found throw exception
            return '';
        }

    }

    private function createTempFreqTable($factor, $group)
    {
        $tempFreqTable = $this->freqTable;
        foreach ($factor as $key => $value) {
            if (in_array($key, $this->vars)) {
                $tempFreqTable[$group][$key] += $value;
            }
        }
        return $tempFreqTable;
    }

    private function calculateImbalance($freqTable)
    {
        $totalImbalance = 0;
        //only work well for two groups
        foreach ($this->vars as $var) {
            $tempImbalance = 0;
            foreach ($this->groups as $group) {
                $tempImbalance = abs($tempImbalance - $freqTable[$group][$var]);
            }
            $totalImbalance += $tempImbalance;
        }
        return $totalImbalance;
    }

    private function getRepatiationValues($hospital_name)
    {
        if (key_exists($hospital_name,$this->hospital_repatriation_value)){
            return $this->hospital_repatriation_value[$hospital_name];
        }
        return 0;
    }

}
