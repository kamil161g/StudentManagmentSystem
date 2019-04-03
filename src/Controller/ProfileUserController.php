<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class ProfileUserController extends AbstractController
{
    public function viewUserProfile() : Response
    {


        return $this->render('profile/userProfile.html.twig');
    }
}