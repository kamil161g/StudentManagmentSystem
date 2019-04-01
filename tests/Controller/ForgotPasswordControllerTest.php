<?php


namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ForgotPasswordControllerTest extends WebTestCase
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

    public function testShouldIfEmailFormIsOk()
    {
        $user = new User();
        $user->setEmail('test1@test.pl');
        $user->setPassword('test123');
        $this->em->persist($user);
        $this->em->flush();

        $email = $user->getEmail();

        $crawler = $this->client->request('GET', '/forgotpassword');

        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Wyślij')->form();

        $form['forgot_password[email]']->setValue($email);

        $this->client->submit($form);
        $this->client->enableProfiler();
        $mailCollector = $this->client->getProfile()->getCollector('swiftmailer');
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Hello Email', $message->getSubject());
        $this->assertSame('kamilgasior07test@gmail.com', key($message->getFrom()));
        $this->assertSame($email, key($message->getTo()));

        $this->client->followRedirect();

        $this->assertStringContainsString(
            'Email został wysłany',
            $this->client->getResponse()->getContent());
        $this->em->remove($user);
        $this->em->flush();
    }

    protected function tearDown(): void
    {
        $this->em->rollback();

    }
}