<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChangePasswordController
 * @package App\Controller
 */
final class ChangePasswordController extends AbstractController
{
    /**
     * @param Request $request
     * @param UserService $userService
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePassword(Request $request, UserService $userService) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ChangePasswordType::class);

        $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $newPassword1 = $form->get('newPassword1')->getViewData();
                $newPassword2 = $form->get('newPassword2')->getViewData();

                $userService->changePassword($this->getUser(), $newPassword1, $newPassword2);

                $this->addFlash('success', 'Hasło zostało zmienione');

                return $this->redirectToRoute('profile', ['user' => $this->getUser()->getId()]);
            }

        return $this->render('profile/changePassword.html.twig',[
            'form' => $form->createView()
        ]);
    }
}