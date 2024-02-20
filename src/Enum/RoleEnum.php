<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class RoleEnum extends Enum
{
    public const MEDECIN = 'Medecin';
    public const PATIENT = 'Patient';
    public const ADMIN = 'Admin';
}
