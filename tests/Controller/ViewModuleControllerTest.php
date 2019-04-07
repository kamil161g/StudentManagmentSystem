<?php


namespace App\Tests\Controller;


use App\Entity\Comment;
use App\Entity\Module;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ViewModuleControllerTest extends WebTestCase
{
    private $client;

    private $em;

    private $user;

    private $module;

    private $comment;

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function setUp(): void
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'test123@test.pl',
            'PHP_AUTH_PW'   => 'test123',
        ]);
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();

        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->user->setRoles(['ROLE_TEACHER']);
        $this->user->setClass('Informatyczna');
        $this->em->persist($this->user);

        $this->module = new Module();
        $this->module->setTitle('defaultTitle');
        $this->module->setText('defaultText');
        $this->module->setAuthor($this->user);
        $this->em->persist($this->module);
        $this->em->flush();

    }


    public function testShouldIfYouCanSeeModule()
    {
        $this->client->request('GET', "/module/".$this->module->getId());

        $this->assertStringContainsString(
            $this->module->getTitle(),
            $this->client->getResponse()->getContent()
            );
        $this->assertStringContainsString(
            $this->module->getText(),
            $this->client->getResponse()->getContent()
        );

        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testShouldIfYouCanAddComment()
    {

        $crawler = $this->client->request('GET', "/module/".$this->module->getId());

        $form = $crawler->selectButton('Dodaj komentarz')->form();

        $form['add_comment[text]']->setValue('defaultText');

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->comment = new Comment();
        $this->comment->setText($form['add_comment[text]']->getValue());
        $this->comment->setCreatedAt(new \DateTime('now'));
        $this->comment->setModule($this->module);
        $this->comment->setAuthor($this->user);
        $this->em->persist($this->comment);
        $this->em->flush();


        $this->assertStringContainsString(
            "Komentarz dodany",
            $this->client->getResponse()->getContent()
        );

    }

    public function testShouldIfYouCanRemoveModule()
    {
        $crawler = $this->client->request('GET', "/module/".$this->module->getId());

        $link = $crawler->selectLink('Usuń moduł')->link();
        $this->client->click($link);


        $this->client->followRedirect();

        $this->assertStringContainsString(
            'Moduł usunięty',
            $this->client->getResponse()->getContent()
        );
    }


    protected function tearDown(): void
    {
        $this->em->rollback();

    }
}