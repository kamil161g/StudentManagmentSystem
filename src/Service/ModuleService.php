<?php


namespace App\Service;


use App\Entity\Module;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ModuleService
{
    private $repository;

    public function __construct(EntityManagerInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Module $module
     * @param User $user
     * @param string $title
     * @param string $text
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insertModule(Module $module, User $user, string $title, string $text)
    {
        $this->repository->getRepository(Module::class)->addNewModule(
            $module,
            $user,
            $title,
            $text
        );
    }
}