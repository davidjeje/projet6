<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Tricks;
use App\Entity\Commentaires;
use App\Entity\Paginator;
use Faker;

class AppFixtures extends Fixture
{
    private $encoder;
    

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    //public const USER_REFERENCE = 'user';
    public function load(ObjectManager $manager/*, User $user*/)
    {
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setNom('Nom'.$i);
            $user->setPrenom('Prenom'.$i);
            $user->setPhoto($i.'.jpg');
            $user->setIsActive(true);
            $user->addRole('ROLE_USER');

            $password = $this->encoder->encodePassword($user, 'pass_1234');
            $user->setPassword($password);

            $manager->persist($user);
        }
        $manager->flush();

        for ($i = 0; $i < 20; $i++) {
            $paginator = new paginator();
            $paginator->setPage($i);
            $paginator->setNbPages('20');
            $paginator->setNomRoute('tricks_show');
            $manager->persist($paginator);
        }
        $manager->flush();

        $user= $manager->getRepository(User::class)->findAll();
        $paginator= $manager->getRepository(Paginator::class)->findAll();
        for ($i = 1; $i < 10; $i++) {
            $trick = new Tricks();
            $trick->setName('trick '.$i);
            $trick->setDescription('Description '.$i);
            $trick->setLevel('Level '.$i);
            $trick->setGroupname('Groupname '.$i);
            $trick->setDateCreation((new \DateTime())->format("d-m-Y"));
            $trick->setDateModification((new \DateTime())->format("d-m-Y"));
         

            $trick->addAuteur($user[$i]);
            $trick->setImage($i.'.jpeg');
            $trick->setSecondeImage($i.'.jpeg');
            $trick->setVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/jl6xnZRi9p8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> ');
            $trick->setSecondeVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/jl6xnZRi9p8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> ');
            $trick->setTroisiemeVideo('<iframe width="560" height="315" src="https://www.youtube.com/embed/jl6xnZRi9p8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> ');
            $trick->setSlug('trick'.$i);
            $manager->persist($trick);
            $Commentaires = new Commentaires();
            $Commentaires->setPaginatorId($paginator[$i]);
            
            $Commentaires->setAutorId($user[$i]);
            
            $Commentaires->setCommentaire('Commentaire '.$i);
            $Commentaires->setDateCommentaire((new \DateTime())->format("d-m-Y"));
            
            $trick->addCommentairesId($Commentaires);
            $manager->persist($Commentaires);
        }


        $manager->flush();
    }
}
