<?php

namespace App\Message;

use App\Entity\CabinetEnAttente;


class RegistrationNotification{

    private $cabinet;

    public function __construct(CabinetEnAttente $cabinet)
    {
        $this->cabinet = $cabinet;
    }

    public function getCabinet(): CabinetEnAttente
    {
        return $this->cabinet;
    }
}