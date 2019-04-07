<?php

namespace App\Repository;

use App\Entity\Module;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Module::class);
    }

    /**
     * @param Module $module
     * @param User $user
     * @param string $title
     * @param string $text
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addNewModule(Module $module, User $user, string $title, string $text)
    {
        $module->setAuthor($user);
        $module->setTitle($title);
        $module->setText($text);
        $this->_em->persist($module);
        $this->_em->flush();
    }

    /**
     * @param Module $module
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addObserver(Module $module, User $user)
    {
        $this->_em->persist($module);
        $this->_em->flush();
    }

    /**
     * @param Module $module
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function removeModule(Module $module)
    {
        $this->_em->remove($module);
        $this->_em->flush();
    }
}
