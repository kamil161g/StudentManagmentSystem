<?php


namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePasswordControllerTest extends WebTestCase
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

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testShouldYouCanChangePassword()
    {
        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->em->persist($this->user);
        $this->em->flush();

        $idUser = $this->user->getId();

        $crawler = $this->client->request('GET', "/profile/changepassword");


        $form = $crawler->selectButton('Zmień hasło')->form();

        $form['change_password[oldPassword]']->setValue(password_hash('test123', PASSWORD_ARGON2I));
        $newPassword1 = $form['change_password[newPassword1]']->setValue(password_hash('test1234', PASSWORD_ARGON2I));
        $newPassword2 = $form['change_password[newPassword2]']->setValue(password_hash('test1234', PASSWORD_ARGON2I));
        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertSame($newPassword1, $newPassword2);


        $this->assertStringContainsString(
            'Hasło zostało zmienione',
            $this->client->getResponse()->getContent()
        );
    }

    protected function tearDown(): void
    {
        $this->em->rollback();
    }

}