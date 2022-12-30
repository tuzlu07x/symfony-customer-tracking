<?php

namespace App\Controller;

use App\Entity\Leave;
use App\Entity\Employee;
use App\Request\LeaveRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class LeaveController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(Employee $employee)
    {
        $leaves = $employee->getLeaves();

        return $employee->serialized($leaves);
    }

    public function add(LeaveRequest $request, Employee $employee)
    {
        $data = $request->validated();
        $leave = new Leave();
        $leave->setEmployee($employee);
        $leave->setStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['start_date']));
        $leave->setEndDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['end_date']));

        $this->entityManager->persist($leave);
        $this->entityManager->flush();

        return $leave->serialized($leave);
    }

    public function update(LeaveRequest $request, Employee $employee, Leave $leave)
    {
        $data = $request->validated();
        $leave->setStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['start_date']));
        $leave->setEndDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['end_date']));

        $this->entityManager->persist($leave);
        $this->entityManager->flush();

        return $leave->serialized($leave);
    }

    public function delete(Employee $employee, Leave $leave)
    {
        $this->entityManager->remove($leave);
        $this->entityManager->flush();

        return $this->json(['message' => 'Leave deleted successfully']);
    }
}
