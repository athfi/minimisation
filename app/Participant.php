<?php

namespace App;

class Participant
{
    public string $id;
    public array $factors;


    /**
     * @param string $id
     * @param array $factors
     */
    public function __construct( string $id, array $factors )
    {
        $this->id = $id;
        $this->factors = $factors;
    }

}
