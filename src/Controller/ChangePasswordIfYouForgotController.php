<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ChangePasswordIfYouForgotType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ChangePasswordIfYouForgotController
 * @package App\Controller
 */
final class ChangePasswordIfYouForgotController extends AbstractController
{

    /**
     * @param Request $request
     * @param UserService $userService
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePassword(Request $request, UserService $userService, User $user)
    {
        $form = $this->createForm(ChangePasswordIfYouForgotType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                $password1 = $form->get('newPassword1')->getViewData();
                $password2 = $form->get('newPassword2')->getViewData();

                $userService->changePasswordIfYouForgot($user, $password1, $password2);

                $this->addFlash('success', 'Hasło zmienione prawidłowo');

                return $this->redirectToRoute('index');
            }

        return $this->render('password/setNewPasswordIfYouForgot.html.twig',[
            'form' => $form->createView()
        ]);
    }
}