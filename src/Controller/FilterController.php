<?php

namespace App\Controller;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilterController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(Request $request)
    {
        $fullName = $request->get('fullName');
        $startDate = $request->get('startDate');
        $endDate = $request->get('endDate');
        $leave = $request->get('leave');
        $notLeave = $request->get('notLeave');

        if ($fullName) {
            $employees = $this->entityManager->getRepository(Employee::class)
                ->searchFullName($fullName);
        }

        if ($startDate && $endDate) {
            if ($notLeave == true) {
                $employees = $this->entityManager->getRepository(Employee::class)
                    ->whereDoestnHave($startDate, $endDate, $notLeave);
            }

            if ($leave == true) {
                $employees = $this->entityManager->getRepository(Employee::class)
                    ->whereDoesHave($startDate, $endDate, $leave);
            }
        }

        return $this->entityManager->getRepository(Employee::class)
            ->serialize($employees);
    }
}
