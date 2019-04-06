<?php


namespace App\Tests\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChangeEmailControllerTest extends WebTestCase
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
    public function testShouldYouCanChangeEmail()
    {
        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->user->setRoles(['ROLE_TEACHER']);
        $this->em->persist($this->user);
        $this->em->flush();

        $crawler = $this->client->request('GET', "/profile/changemail");

        $form = $crawler->selectButton('Zmień email')->form();

        $form['change_email[oldEmail]']->setValue(('test123@test.pl'));
        $form['change_email[newEmail]']->setValue('test1234@test.pl');

        $this->client->submit($form);

        $this->client->followRedirect();

        $this->assertNotSame(
            $form['change_email[oldEmail]']->getValue(),
            $form['change_email[newEmail]']->getValue()
    );
        $this->assertSame($this->user->getEmail(), $form['change_email[newEmail]']->getValue());

    }

    protected function tearDown(): void
    {
        $this->em->rollback();
    }

}