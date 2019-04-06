<?php


namespace App\Tests\Controller;


use App\Entity\Module;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AddNewModuleControllerTest
 * @package App\Tests\Controller
 */
class AddNewModuleControllerTest extends WebTestCase
{
    private $em;
    private $client;
    private $user;
    private $module;


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
    public function testShouldIfYouCanAddNewModule()
    {
        $this->user = new User();
        $this->user->setEmail('test123@test.pl');//password is test123
        $this->user->setPassword(password_hash('test123', PASSWORD_ARGON2I));
        $this->user->setRoles(['ROLE_TEACHER']);
        $this->em->persist($this->user);
        $this->em->flush();

        $this->module = new Module();

        $crawler = $this->client->request('GET', '/addnewmodule');

        $form = $crawler->selectButton('Dodaj')->form();

        $form['add_module[title]']->setValue(('defaultTitle'));
        $form['add_module[text]']->setValue('defaultText');


        $this->client->submit($form);

        $this->client->followRedirect();

        $this->module->setTitle($form['add_module[title]']->getValue());
        $this->module->setText($form['add_module[text]']->getValue());
        $this->module->setAuthor($this->user);
        $this->em->persist($this->module);
        $this->em->flush();

        $this->assertSame($this->user, $this->module->getAuthor());
        $this->assertSame($this->module->getTitle(), $form['add_module[title]']->getValue());
        $this->assertSame($this->module->getText(), $form['add_module[text]']->getValue());

        $this->assertStringContainsString(
            'Moduł został dodany',
            $this->client->getResponse()->getContent());
    }

    protected function tearDown(): void
    {
        $this->em->rollback();
    }
}