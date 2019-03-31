<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ForgotPasswordControllerTest extends WebTestCase
{

    public function testShouldIfEmailFormIsOk()
    {
        $client = static::createClient();



        $crawler = $client->request('GET', '/forgotpassword');

        $this->assertEquals('200', $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Wyślij')->form();

        $form['forgot_password[email]']->setValue('kamilgasior07@gmail.com');

        $client->submit($form);
        $client->enableProfiler();
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Hello Email', $message->getSubject());
        $this->assertSame('kamilgasior07test@gmail.com', key($message->getFrom()));
        $this->assertSame('kamilgasior07@gmail.com', key($message->getTo()));

        $client->followRedirect();

        $this->assertStringContainsString(
            'Email został wysłany',
            $client->getResponse()->getContent());
    }
}