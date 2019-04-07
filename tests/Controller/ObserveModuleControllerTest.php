<?php


namespace App\Tests\Controller;


use App\Entity\Module;
use App\Entity\Observer;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ObserveModuleControllerTest extends WebTestCase
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
    public function testShouldIfYouCanObservationModule()
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
        $this->em->flush();


        $crawler = $this->client->request('GET', "/module/".$this->module->getId());

        $link = $crawler->selectLink('Obserwuj moduł')->link();
        $this->client->click($link);


        $this->client->followRedirect();

        $this->observer = new Observer();
        $this->observer->setModule($this->module);
        $this->observer->setUser($this->user);
        $this->observer->setIsAccept(true);
        $this->em->persist($this->observer);

        $this->assertStringContainsString(
            'Obserwujesz ten moduł',
            $this->client->getResponse()->getContent()
        );
    }

    protected function tearDown() :void
    {
        $this->em->rollback();
    }

}