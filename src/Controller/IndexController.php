<?php


namespace App\Controller;


use App\Entity\Pupil;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getAllPupils()
    {
        $employeeRepository = $this->objectManager
            ->getRepository(Pupil::class);



        return $this->render('index.html.twig',[
            'pupils' => $employeeRepository->findAll()
        ]);


    }
}