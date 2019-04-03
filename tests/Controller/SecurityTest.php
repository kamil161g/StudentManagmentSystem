<?php


namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityTest extends WebTestCase
{
    private $client;

    private $em;

    private $user;

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

    public function testShouldIfYouCanLogIn()
    {
        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->em->persist($this->user);
        $this->em->flush();


        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Zaloguj się')->form();

        $form['email']->setValue('test123@test.pl');
        $form['password']->setValue('test123');

        $this->client->submit($form);


        $this->client->followRedirect();

        $this->assertSame($this->user->getRoles(), ['ROLE_USER']);



    }

    protected function tearDown(): void
    {
        $this->em->rollback();

    }


}