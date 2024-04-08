<?php

namespace App\Enums;

enum RelationType: string
{
    case FATHER = 'Father';
    case MOTHER = 'Mother';
    case SISTER = 'Sister';
    case BROTHER = 'Brother';
}
