<?php


namespace App\Controller;


use App\Form\ChangeEmailType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ChangeEmailController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeEmail(Request $request, UserService $userService) : Response
    {
//        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ChangeEmailType::class);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $oldEmail = $form->get('oldEmail')->getViewData();
                $newEmail = $form->get('newEmail')->getViewData();

                $userService->changeEmail($this->getUser(), $oldEmail, $newEmail);

                $this->addFlash('success', 'Email został zmieniony');

                return $this->redirectToRoute('profile', [ 'user' => $this->getUser()->getId() ]);
            }

        return $this->render('profile/changeEmail.html.twig',[
            'form' => $form->createView()
        ]);

        }


}