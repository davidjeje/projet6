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
		/*$tricks = $this->entityManager
            ->getRepository(Tricks::class)
            ->findAll();*/

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

		//$id=$tricks[0]->getId();
		$userId=$user[0]->getId();
		$token=$user[0]->getToken();
		//$userId->setIsActive(true);
		/*$session = new Session();
        $session->start();*/
    	return [
        ['/'],
        ['users/new/user'],
        //['users/validate/'.$token.''], //Voir avec Fadyl
        ['users/login/user'],
        //['users/forgot/password'],
        //['users/'.$userId.'/show'],
        //['users/'.$userId.'/edit/user'],
        //['users/logout/user'],
           
    	];
	}
    
    // You are calling "form_widget" for field "user" which has already been rendered before, trying to render fields which were already rendered is deprecated since Symfony 4.2 and will throw an exception in 5.0.
    //1x in UserControllerTest::testChangeTokenUser from App\Tests\Controller


    /*public function testChangeTokenUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/change/{token}');

        // Premier test
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        //$this->assertGreaterThan(0, $crawler->filter('h2')->count());
        /*$this->assertGreaterThan
        (0, $crawler->filter('html:contains("inscription")')->count());*/
        //Troisième test
        /*$this->assertContains('Toutes les figures',
    	$client->getResponse()->getContent());*/
    	//$this->assertContains('foo', $client->getResponse()->getContent());
    	//Quatrième test

    	/*$link = $crawler
    	->filter('a:contains("Inscription")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);*/
    //}

	/*public function testValidateTokenUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/validate/{token}');
		$this->assertTrue(
    	$client->getResponse()->isRedirect('users/login/user')
    	// if the redirection URL was generated as an absolute URL
    	// $client->getResponse()->isRedirect('http://localhost/demo/contact')
		);
        // Premier test
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        //$this->assertGreaterThan(0, $crawler->filter('h2')->count());
        /*$this->assertGreaterThan
        (0, $crawler->filter('html:contains("inscription")')->count());*/
        //Troisième test
        /*$this->assertContains('Toutes les figures',
    	$client->getResponse()->getContent());*/
    	//$this->assertContains('foo', $client->getResponse()->getContent());
    	//Quatrième test

    	/*$link = $crawler
    	->filter('a:contains("Inscription")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);*/
    //}

   
    /* 1x: You are calling "form_widget" for field "" which has already been rendered before, trying to render fields which were already rendered is deprecated since Symfony 4.2 and will throw an exception in 5.0.
    1x in UserControllerTest::testForgotPasswordUser from App\Tests\Controller


    public function testForgotPasswordUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/forgot/password');

        // Premier test
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        //$this->assertGreaterThan(0, $crawler->filter('h2')->count());
        /*$this->assertGreaterThan
        (0, $crawler->filter('html:contains("inscription")')->count());*/
        //Troisième test
        /*$this->assertContains('Toutes les figures',
    	$client->getResponse()->getContent());*/
    	//$this->assertContains('foo', $client->getResponse()->getContent());
    	//Quatrième test

    	/*$link = $crawler
    	->filter('a:contains("Inscription")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);*/
    //}

    /*public function testEditUser()
    {
    	$kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
		/*$tricks = $this->entityManager
            ->getRepository(Tricks::class)
            ->findAll();*/

       /* $user = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

		//$id=$tricks[0]->getId();
		$userId=$user[0]->getId();
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/'.$userId.'/edit/user');

        // Premier test
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        $this->assertGreaterThan(0, $crawler->filter('h5')->count());
        /*$this->assertGreaterThan
        (0, $crawler->filter('html:contains("inscription")')->count());*/
        //Troisième test
        /*$this->assertContains('Toutes les figures',
    	$client->getResponse()->getContent());*/
    	//$this->assertContains('foo', $client->getResponse()->getContent());
    	//Quatrième test

    	/*$link = $crawler
    	->filter('a:contains("Inscription")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);*/
	//}

    public function testLogoutUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/users/logout/user');

        // Premier test
        $this->assertTrue($client->getResponse()->isNotFound());
       	//$this->assertEquals(200, $client->getResponse()->getStatusCode());
        
    }

    /*public function testDeleteUser()
    {
    	$client = static::createClient();
        $crawler = $client->request('DELETE', '/users/{id}/delete/user');

        // Premier test
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        //$this->assertGreaterThan(0, $crawler->filter('h2')->count());
        /*$this->assertGreaterThan
        (0, $crawler->filter('html:contains("inscription")')->count());*/
        //Troisième test
        /*$this->assertContains('Toutes les figures',
    	$client->getResponse()->getContent());*/
    	//$this->assertContains('foo', $client->getResponse()->getContent());
    	//Quatrième test

    	/*$link = $crawler
    	->filter('a:contains("Inscription")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);*/
    //}
}