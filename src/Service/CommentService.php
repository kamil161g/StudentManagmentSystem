<?php


namespace App\Service;


use App\Entity\Comment;
use App\Entity\Module;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentService
{

    /**
     * @var EntityManagerInterface
     */
    private $repository;

    /**
     * CommentService constructor.
     * @param EntityManagerInterface $repository
     */
    public function __construct(EntityManagerInterface $repository)
    {

        $this->repository = $repository;
    }

    /**
     * @param Module $module
     * @param User $user
     * @param Comment $comment
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function setComment(Module $module, User $user, Comment $comment)
    {
        $this->repository->getRepository(Comment::class)->insertComment($module, $user, $comment);
    }
}