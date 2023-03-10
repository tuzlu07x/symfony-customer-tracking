<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EmployeeRequest extends BaseRequest
{
    #[Type('string')]
    protected $first_name;

    #[Type('string')]
    protected $last_name;

    #[Type('string')]
    protected $social_security_number;

    #[Type('string')]
    protected $start_date;

    #[Type('string')]
    protected $end_date;

    #[Type('string')]
    protected $citizen_number;
}
