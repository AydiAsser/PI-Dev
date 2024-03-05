<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class RoleEnum extends Enum
{
    public const MEDECIN = 'medecin';
    public const PATIENT = 'patient';
    public const ADMIN = 'Admin';
}
