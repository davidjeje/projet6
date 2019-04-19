<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Tricks;
use App\Entity\Commentaires;
class AppFixtures extends Fixture
{
	private $encoder;

public function __construct(UserPasswordEncoderInterface $encoder)
{
    $this->encoder = $encoder;
}

// ...
//public const USER_REFERENCE = 'user';
public function load(ObjectManager $manager)
{
	for ($i = 0; $i < 20; $i++) 
	{
	$user = new User();
    $user->setEmail('user'.$i.'com');
    $user->setNom('user'.$i);
    $user->setPrenom('user'.$i);
    $user->setPhoto('user'.$i);

    $password = $this->encoder->encodePassword($user, 'pass_1234');
    $user->setPassword($password);

    $manager->persist($user);
	} 

	for ($i = 0; $i < 20; $i++) 
      {
         $trick = new Tricks();
         $trick->setName('trick '.$i);
         $trick->setDescription('Description '.$i);
         $trick->setLevel('Level '.$i);
         $trick->setGroupname('Groupname '.$i);
         $trick->setDateCreation('DateCreation '.$i);
         $trick->setDateModification('DateModification '.$i);
         //$trick->addAuteur($this->getReference(AppFixtures:: 'user'.$i));
         $trick->setImage('http://mementoski.com/wp-content/uploads/2016/07/header-snowboard.jpg'.$i);
         $trick->setSecondeImage('http://mementoski.com/wp-content/uploads/2016/07/header-snowboard.jpg'.$i);
         $trick->setVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/jl6xnZRi9p8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> '.$i);
          $trick->setSecondeVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/jl6xnZRi9p8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> '.$i);
         $trick->setTroisiemeVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/jl6xnZRi9p8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> '.$i);
         $manager->persist($trick);
      }

     /* for ($i = 0; $i < 20; $i++) {
            $Commentaires = new Commentaires();
            //$Commentaires->setAutorId($this->getReference('user1'));

            $Commentaires->setCommentaire('Commentaire '.$i);
            //$Commentaires->setDateCommentaire('DateCommentaire '.$i);
            
            $manager->persist($Commentaires);
        }*/
    $manager->flush();
    $this->addReference('user1', $user);
    $this->addReference('trick'.$i, $trick);
   // $this->addReference('commentaires'.$i, $Commentaires);
    //$this->addReference(self::USER_REFERENCE, $user);
}

}

