<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

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
     * @var object|string
     */
    private $user;

    /**
     * UserService constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $repository
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $repository)
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

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changeAvatar(User $user)
    {
        $this->repository->getRepository(User::class)->updateAvatar($user);
    }

    /**
     * @param User $user
     * @param string $newPassword1
     * @param string $newPassword2
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function changePassword(
        User $user,
        string $newPassword1,
        string $newPassword2
    )
    {
        if($newPassword1 === $newPassword2){
            $hashPassword = $this->passwordEncoder->encodePassword($user, $newPassword1);
            $this->repository->getRepository(User::class)->updatePassword($user, $hashPassword);
        }
    }
}