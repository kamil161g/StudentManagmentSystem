<?php


namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangePasswordIfYouForgotControllerTest extends WebTestCase
{
    private $client;

    private $em;


    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $this->em->beginTransaction();
    }


    public function testShouldIfYouCanSetNewPassword()
    {


        $user = new User();
        $user->setEmail('test1@test.pl');
        $user->setPassword('test123');
        $this->em->persist($user);
        $this->em->flush();

       $id = $user->getId();

        $crawler = $this->client->request('GET', "/changepasswordifyouforgot/$id");

        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Zmień hasło')->form();

        $password1 = $form['change_password_if_you_forgot[newPassword1]']->setValue('kamil1234');
        $password2 = $form['change_password_if_you_forgot[newPassword2]']->setValue('kamil1234');

        $this->assertSame($password1, $password2);

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertStringContainsString('Hasło zmienione prawidłowo',
            $this->client->getResponse()->getContent());

        $this->em->remove($user);
        $this->em->flush();
    }

    protected function tearDown(): void
    {
        $this->em->rollback();

    }
}