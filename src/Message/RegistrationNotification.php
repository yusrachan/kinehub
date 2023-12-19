<?php

namespace App\Message;

use App\Entity\Cabinet;

class RegistrationNotification{

    private $cabinet;

    public function __construct(Cabinet $cabinet)
    {
        $this->cabinet = $cabinet;
    }

    public function getCabinet(): Cabinet
    {
        return $this->cabinet;
    }
}