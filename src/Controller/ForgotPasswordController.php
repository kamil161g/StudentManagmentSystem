<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\ForgotPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ForgotPasswordController extends AbstractController
{

    public function sendEmailWithChangePassword(\Swift_Mailer $mailer, Request $request)
    {

        $user = new User();

        $form = $this->createForm(ForgotPasswordType::class, $user);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if($this->getDoctrine()->getRepository(User::class)->findOneBy([
                    'email' => $form->get('email')->getViewData()
                ]
                )){

                    $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('kamilgasior07test@gmail.com')
                        ->setTo($form->get('email')->getViewData())
                        ->setBody(
                            $this->renderView(
                                'emails/changePassword.html.twig'
                            ),
                            'text/html'
                        );

                    $mailer->send($message);

                    $this->addFlash('success', 'Email został wysłany');

                    return $this->redirectToRoute('index');
                }else{
                    $this->addFlash('error', 'Konto z tym emailem nie istnieje');

                }


            }


        return $this->render('emails/forgotpassword.html.twig',[
            'form' => $form->createView()
        ]);
    }
}