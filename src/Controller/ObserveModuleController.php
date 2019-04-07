<?php


namespace App\Controller;


use App\Entity\Module;
use App\Entity\User;
use App\Service\ModuleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ObserveModuleController
 * @package App\Controller
 */
final class ObserveModuleController extends AbstractController
{
    /**
     * @param Module $module
     * @param User $user
     * @param ModuleService $moduleService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function observeModule(Module $module, User $user, ModuleService $moduleService)
    {
        $moduleService->insertObserver($module, $user);

        $this->addFlash('success', 'Obserwujesz ten moduł');

        return $this->redirectToRoute('view_module', ['module' => $module->getId()]);
    }
}