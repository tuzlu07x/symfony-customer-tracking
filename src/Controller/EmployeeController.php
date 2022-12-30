<?php

namespace App\Controller;

use App\Entity\Employee;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class EmployeeController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function index(): Response
    {
        $employees = $this->entityManager->getRepository(Employee::class)->findAll();

        return $this->json([
            'employees' => $employees
        ], 200, [], ['groups' => ['main']]);
    }
}
