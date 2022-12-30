<?php

namespace App\Controller;

use App\Entity\Leave;
use App\Entity\Employee;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FilterController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function index(Request $request): JsonResponse
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
            if ($leave) {
                $employees = $this->entityManager->getRepository(Employee::class)
                    ->searchBetweenDates($startDate, $endDate, true);
            } elseif ($notLeave) {
                $employees = $this->entityManager->getRepository(Employee::class)
                    ->searchBetweenDates($startDate, $endDate, false);
            } else {
                $employees = $this->entityManager->getRepository(Employee::class)
                    ->searchBetweenDates($startDate, $endDate);
            }
            $employees = $this->entityManager->getRepository(Employee::class)
                ->searchBetweenDates($startDate, $endDate);
        }

        return new JsonResponse($employees);
    }
}
