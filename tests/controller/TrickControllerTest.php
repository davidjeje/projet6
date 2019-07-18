<?php
// tests/Controller/UsersControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Tricks;

/**
 * @Route("/tricks")
 */
class TrickControllerTest extends WebTestCase
{
	/**
    * @var \Doctrine\ORM\EntityManager
    */
    private $entityManager;

    
	public function testAccueilTrick()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/');
        // Premier test
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        $this->assertGreaterThan(0, $crawler->filter('h2')->count());
        //Troisième test
        $this->assertContains('Toutes les figures',
    	$client->getResponse()->getContent());
    	//Quatrième test
    	$this->assertContains('foo', $client->getResponse()->getContent());
    	//Cinquième test
    	$link = $crawler
    	->filter('a:contains("Plus de Détails")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);

    }

    public function testAcTrick()
    {
    	$client = static::createClient();
    	//Ajout de cette fonctionnalité mais lors de la vérification des assertion je ne vois pas que ça c'est ajouté.
    	$client->enableProfiler();
        $crawler = $client->request('GET', '/');

        if ($profile = $client->getProfile()) 
        {
            // check the number of requests
            $this->assertLessThan(5,$profile->getCollector('db')->getQueryCount());
        }
        

		$crawler->attr('class');
		$client->clickLink('Plus de Détails');
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
		$tricks = $this->entityManager
            ->getRepository(Tricks::class)
            ->findAll();

        

		
        $slug=$tricks[0]->getSlug();
    	return [
        ['/'],
        ['/trick/ajax'],
        ['/new/trick'], 
        ['/'.$slug.'/1/show/one/trick'],
        ['/'.$slug.'/edit/trick'],
        ['/'.$slug.'/1/editImage'],
        ['/'.$slug.'/1/editVideo'],
           
    	];
	}

	public function testAjaxTrick()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/trick/ajax');
        // Premier test
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //Deuxième test
        $this->assertGreaterThan(0, $crawler->filter('h4')->count());
        //Troisième test
        $this->assertContains('Plus de Détails',
    	$client->getResponse()->getContent());
        /* Pourquoi ce test ne fonctionne pas ?
    	$this->assertContains('foo', $client->getResponse()->getContent());*/
    	//Quatrième test

    	$link = $crawler
    	->filter('a:contains("Plus de Détails")') // find all links with the text "Greet"
    	->eq(1) // select the second link in the list
    	->link();
		$crawler = $client->click($link);
		$client->xmlHttpRequest('GET', '/trick/ajax', ['#loading']);

    }

    

    /*public function testCreateTrick()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/{id}/new/trick');
        
        $this->assertGreaterThan(0, $crawler->filter('h2')->count());
    	
    	$client->catchExceptions(false);
		
    }

    
    public function testDetailTrick()
    {
    	$client = static::createClient();

        $crawler = $client->request('GET', '/{id}/{page}/show');
        
        $this->assertGreaterThan(0, $crawler->filter('h2')->count());
        
    	$this->assertContains('foo', $client->getResponse()->getContent());
    	//Quatrième test
    	//Ne fonctionne pas lors de l'inssertion.
    	$client->catchExceptions(false);

    }*/

    
    public function testEditImageTrick()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/{id}/{numberImage}/editImage');
        
        $this->assertGreaterThan(0, $crawler->filter('h2')->count());
        
    	$this->assertContains('foo', $client->getResponse()->getContent());
    	
    }

    public function testEditVideoTrick()
    {
    	$client = static::createClient();
        $crawler = $client->request('GET', '/{id}/{numberVideo}/editVideo');
        
        $this->assertGreaterThan(0, $crawler->filter('h2')->count());
        
    	//$this->assertContains('foo', $client->getResponse()->getContent());
    	
    }

   
}