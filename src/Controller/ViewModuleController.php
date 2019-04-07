<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Module;
use App\Form\AddCommentType;
use App\Repository\CommentRepository;
use App\Repository\ModuleRepository;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ViewModuleController
 * @package App\Controller
 */
final class ViewModuleController extends AbstractController
{

    /**
     * @param Module $module
     * @param EntityManagerInterface $repository
     * @param Request $request
     * @param CommentService $commentService
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function viewModule(
        Module $module,
        EntityManagerInterface $repository,
        Request $request,
        CommentService $commentService) : Response
    {
        $comment = new Comment();

        $form = $this->createForm(AddCommentType::class, $comment);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $commentService->setComment($module, $this->getUser(), $comment);

                $this->addFlash('success', 'Komentarz dodany');

                return $this->redirectToRoute('view_module', ['module' => $module->getId()]);
            }

        return $this->render('module/viewModule.html.twig',[
            'module' => $repository->getRepository(Module::class)->findOneBy(['id' => $module]),
            'comment' => $repository->getRepository(Comment::class)->findBy(['module' => $module]),
                'form' => $form->createView()
                ]
            );
    }

    /**
     * @param Module $module
     * @param ModuleRepository $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeModule(Module $module, ModuleRepository $repository)
    {
        $repository->removeModule($module);

        $this->addFlash('success', 'Moduł usunięty');

        return $this->redirectToRoute('index');
    }

}