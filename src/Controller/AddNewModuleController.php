<?php


namespace App\Controller;


use App\Entity\Module;
use App\Form\AddModuleType;
use App\Service\ModuleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddNewModuleController
 * @package App\Controller
 */
final class AddNewModuleController extends AbstractController
{
    /**
     * @param Request $request
     * @param ModuleService $moduleService
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addModule(Request $request, ModuleService $moduleService)
    {
        $module = new Module();

        $form = $this->createForm(AddModuleType::class);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){


                $moduleService->insertModule(
                    $module,
                    $this->getUser(),
                    $form->get('title')->getViewData(),
                    $form->get('text')->getViewData());

                $this->addFlash('success', 'Moduł został dodany');

                return $this->redirectToRoute('index');

            }

        return $this->render("module/addNewModule.html.twig",[
            'form' => $form->createView()
        ]);
    }
}