<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Type;

class LeaveRequest extends BaseRequest
{
    #[Type('string')]
    #[NotNull(message: 'Start date is required')]
    protected $start_date;

    #[Type('string')]
    #[NotNull(message: 'End date is required')]
    protected $end_date;
}
