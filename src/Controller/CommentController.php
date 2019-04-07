<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Module;
use App\Form\AddCommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{

    /**
     * @var CommentRepository
     */
    private $repository;

    public function __construct( CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Module $module
     * @param Comment $comment
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function editComment(Module $module, Comment $comment, Request $request)
    {
        $form = $this->createForm(AddCommentType::class, $comment);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $this->repository->insertComment($module, $this->getUser(), $comment);

                $this->addFlash('success', 'Komentarz zedytowany');

                return $this->redirectToRoute('view_module', ['module' => $module->getId()]);
            }

        return $this->render("comment/editComment.html.twig",[
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Comment $comment
     * @param Module $module
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeComment(Comment $comment, Module $module)
    {
        $this->repository->removeComment($comment);

        $this->addFlash('success', 'Komentarz usunięty');

        return $this->redirectToRoute('view_module', ['module' => $module->getId()]);
    }
}