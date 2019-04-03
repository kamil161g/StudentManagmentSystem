<?php


namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ChangeAvatarControllerTest extends WebTestCase
{
    private $client;

    private $em;

    private $user;

    private $photo;

    protected function setUp(): void
    {
        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => 'test123@test.pl',
            'PHP_AUTH_PW'   => 'test123',
        ]);
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


    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testShouldIfYouCanChangeAvatar()
    {
        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->user->setFile($this->photo);
        $this->em->persist($this->user);
        $this->em->flush();

        $idUser = $this->user->getId();

        $crawler = $this->client->request('GET', "/profile/changeavatar/$idUser");


        $form = $crawler->selectButton('Zmień avatara')->form();

        $newPhoto = $form['change_avatar[file]']->setValue($this->photo);

        $this->client->submit($form);

        $this->client->followRedirect();



        $this->assertStringContainsString(
            'Avatar został zmieniony',
            $this->client->getResponse()->getContent()
        );
    }

    protected function tearDown(): void
    {
        $this->em->rollback();

    }

}