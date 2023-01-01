<?php

namespace App\Controller;

use DateTime;
use App\Entity\Employee;
use App\Request\EmployeeRequest;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index()
    {
        $employees = $this->entityManager->getRepository(Employee::class)->findAll();

        return $this->entityManager->getRepository(Employee::class)
            ->serialize($employees);
    }

    public function add(EmployeeRequest $request)
    {
        $data = $request->validated();
        $employee = new Employee();
        $employee->setFirstName($data['first_name']);
        $employee->setLastName($data['last_name']);
        $employee->setStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['start_date']));
        $employee->setEndDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['end_date']));
        $employee->setSocialSecurityNumber($data['social_security_number']);
        $employee->setCitizenNumber($data['citizen_number']);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $this->json($employee);
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();
        $employee->setFirstName($data['first_name']);
        $employee->setLastName($data['last_name']);
        $employee->setStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['start_date']));
        $employee->setEndDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['end_date']));
        $employee->setSocialSecurityNumber($data['social_security_number']);
        $employee->setCitizenNumber($data['citizen_number']);

        $this->entityManager->persist($employee);
        $this->entityManager->flush();

        return $this->entityManager->getRepository(Employee::class)->serialize($employee);
    }

    public function delete(Employee $employee)
    {
        $this->entityManager->remove($employee);
        $this->entityManager->flush();

        return $this->json(['message' => 'Employee deleted']);
    }
}
