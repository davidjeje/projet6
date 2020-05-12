<?php
// tests/Controller/UsersControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * @Route("/users")
 */
class UserControllerTest extends WebTestCase 
{
	/**
    * @var \Doctrine\ORM\EntityManager
    */
    private $entityManager;

	public function testNewUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/new/user');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertGreaterThan(0, $crawler->filter('h5')->count());

        $this->assertGreaterThan
        (0, $crawler->filter('html:contains("inscription")')->count());

    	$this->assertContains('foo', $client->getResponse()->getContent());

    	$link = $crawler
    	->filter('a:contains("Inscription")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);

    }

     public function testChangeLoginUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/login/user');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertGreaterThan(0, $crawler->filter('h5')->count());

        $this->assertGreaterThan
        (0, $crawler->filter('html:contains("Se connecter")')->count());
        
    	$this->assertContains('foo', $client->getResponse()->getContent());
    	
    	$link = $crawler
    	->filter('a:contains("Connexion")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);
		$form = $crawler->selectButton('Connexion')->form();

		// set some values
		$form['_username'] = 'user0@gmail.com';
		$form['_password'] = 'pass_1234';

		// submit the form
		$crawler = $client->submit($form);
		

    }

    /**
 	*@dataProvider provideUrls
 	*/
	public function testPageIsSuccessful($url)
	{
    	$client = self::createClient();
    	$client->request('GET', $url);

    	$this->assertTrue($client->getResponse()->isSuccessful());
	}

	public function provideUrls()
	{
		$kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
		

        /*$user = $this->entityManager
            ->getRepository(User::class)
            ->findAll();*/
		
    	return [
        ['/'],
        ['users/new/user'],
        
        ['users/login/user'],
        
           
    	];
	}
    
    
    	
    

    
}