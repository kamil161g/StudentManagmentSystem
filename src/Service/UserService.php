<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $repository;

    /**
     * UserService constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $repository
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $repository)
    {

        $this->passwordEncoder = $passwordEncoder;
        $this->repository = $repository;
    }

    /**
     * @param User $user
     * @param string $password
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNewUser(User $user, string $password)
    {
        $hashPassword = $this->passwordEncoder->encodePassword($user, $password);
        $this->repository->getRepository(User::class)->insertUser($user, $hashPassword);
    }

    /**
     * @param User $user
     * @param string $password1
     * @param string $password2
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePasswordIfYouForgot(User $user, string $password1, string $password2)
    {
        if($password1 === $password2){
            $hashPassword = $this->passwordEncoder->encodePassword($user, $password1);
            $this->repository->getRepository(User::class)->insertUser($user, $hashPassword);
        }

    }
}