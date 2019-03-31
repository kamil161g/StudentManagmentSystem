<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class RegistrationTest extends WebTestCase
{

    private $client;

    private $em;

    private $photo;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->photo = new UploadedFile(
            'public/uploads/photo.jpg',
            'photo.jpg',
            'image/jpeg',
            null
        );
        $this->client->disableReboot();
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $this->em->beginTransaction();

    }

    public function testShouldCheckIfYouAddedNewUser()
    {



        $crawler = $this->client->request('GET', '/registration');


        $this->assertEquals('200', $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Zarejestruj się')->form();

        $form['registration_form[email]']->setValue('kamilgasior@o2.pl');
        $form['registration_form[password]']->setValue('default123');
        $form['registration_form[name]']->setValue('Kamil');
        $form['registration_form[surname]']->setValue('Gąsior');
        $form['registration_form[age]']->setValue('2009-1-1');
        $form['registration_form[class]']->setValue('Informatyczna');
        $form['registration_form[file]']->setValue($this->photo);


        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertStringContainsString(
            'Rejestracja przebiegła pomyślnie',
            $this->client->getResponse()->getContent());

    }

    protected function tearDown(): void
    {
        $this->em->rollback();

    }


}