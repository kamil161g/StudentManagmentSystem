<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ChangeAvatarType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ChangeAvatarController
 * @package App\Controller
 */
final class ChangeAvatarController extends AbstractController
{
    /**
     * @param User $user
     * @param Request $request
     * @param UserService $userService
     * @return Response
     */
    public function changeAvatar(User $user, Request $request, UserService $userService) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ChangeAvatarType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                $userService->changeAvatar($this->getUser());

                $this->addFlash('success', 'Avatar został zmieniony');

                return $this->redirectToRoute('profile', ['user' => $user->getId()]);
            }

        return $this->render('profile/avatar/changeAvatar.html.twig',[
            'form' => $form->createView()
        ]);
    }
}