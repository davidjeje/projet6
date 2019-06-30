<?php

namespace App\Repository;

use App\Entity\Commentaires;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method Commentaires|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaires|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaires[]    findAll()
 * @method Commentaires[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Commentaires::class);
    }

    // /**
    //  * @return Commentaires[] Returns an array of Commentaires objects
    //  */
    
    public function nombreCommentaire($firstResult, $maxResult, $trickId)
    {
        return
            $this->createQueryBuilder('cde')
            ->setfirstResult($firstResult)
            ->where('cde.figureId = :val')
            ->setParameter('val', $trickId)
            ->orderBy('cde.id', 'DESC')
            ->setMaxResults($maxResult)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère une liste de commentaires triés et paginés.
     *
     * @param int $page         Le numéro de la
     *                          page
     * @param int $nbMaxParPage Nombre maximum de commentaire par page
     *
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     *
     * @return Paginator
     */
    public function paginationCommentaire($page, $nbMaxParPage, $trick)
    {
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas');
        }

        if (!is_numeric($nbMaxParPage)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $nbMaxParPage est incorrecte (valeur : ' . $nbMaxParPage . ').'
            );
        }
    
        $qb = $this->createQueryBuilder('abc')
            ->where('CURRENT_DATE() >= abc.dateCommentaire')
            ->andWhere('abc.figureId = :val')
            ->setParameter('val', $trick)
            ->orderBy('abc.dateCommentaire', 'DESC');
        
        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbMaxParPage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbMaxParPage);
        $paginator = new Paginator($query);

        if (($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demandée n\'existe pas.'); // page 404, sauf pour la première page
        }

        return $paginator;
    }


    public function DscCommentaire()
    {
        return $this->createQueryBuilder('cde')
            ->orderBy('cde.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
