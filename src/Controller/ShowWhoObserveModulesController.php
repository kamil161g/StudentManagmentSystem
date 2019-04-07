<?php


namespace App\Controller;


use App\Entity\Module;
use App\Entity\Observer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ShowWhoObserveModulesController
 * @package App\Controller
 */
final class ShowWhoObserveModulesController extends AbstractController
{
    /**
     * @param Module $module
     * @param EntityManagerInterface $reposiotry
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showWhoObserveThisModule(Module $module, EntityManagerInterface $reposiotry) : Response
    {
        return $this->render("module/showWhoObserveThisModule.html.twig",[
            'user' => $reposiotry->getRepository(Observer::class)->findBy(['module' => $module])
            ]);
    }
}