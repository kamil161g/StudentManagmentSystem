<?php


namespace App\Tests\Controller;


use App\Entity\Module;
use App\Entity\Observer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowWhoObserveModulesControllerTest extends WebTestCase
{
    private $client;

    private $em;

    private $user;

    private $module;

    private $observer;

    protected function setUp(): void
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'test123@test.pl',
            'PHP_AUTH_PW'   => 'test123',
        ]);
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();

    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testShouldWhoObserveThisModule()
    {
        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->user->setRoles(['ROLE_TEACHER']);
        $this->em->persist($this->user);

        $this->module = new Module();
        $this->module->setTitle('defaultTitle');
        $this->module->setText('defaultText');
        $this->module->setAuthor($this->user);
        $this->em->persist($this->module);


        $this->observer = new Observer();
        $this->observer->setUser($this->user);
        $this->observer->setModule($this->module);
        $this->em->persist($this->observer);
        $this->em->flush();

       $crawler = $this->client->request('GET', '/whobserve/'.$this->module->getId());

       $this->assertStringContainsString(
           $this->user->getEmail(),
           $this->client->getResponse()->getContent()
       );
    }

    protected function tearDown() :void
    {
        $this->em->rollback();
    }

}