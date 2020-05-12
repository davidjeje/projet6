<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UsersRepository;
use App\Entity\Tricks;
use App\Form\TricksType;
use App\Form\TricksEditType;
use App\Repository\TricksRepository;
use App\Entity\Paginator;
use App\Form\PaginatorType;
use App\Repository\PaginatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/commentaires")
 */
class CommentairesController extends AbstractController
{
    // Cette fonction permet de retourner tous les commentaires du site.
    /**
     * @Route("/", name="commentaires_index", methods="GET")
     */
    public function index(CommentairesRepository $commentairesRepo): Response
    {
        return $this->render('commentaires/index.html.twig', ['commentaires' => $commentairesRepo->findAll()]);
    }

    /**
     * @Route("/commentaire/ajax", name="commentaire_ajax", methods="GET|POST")
     */
    public function ajaxEnAction(Request $request, CommentairesRepository $commentairesRepo)
    {
        //Récupère l'id du premier commentaire de la page demandé.
        $firstResultId = $request->request->get('id');
        //Récupère l'id de la figure.
        $trickId = $request->request->get('trickId');
        //Cette variable stock la fonction située dans le repository commentaire et qui elle même stock des valeurs pour lui permettre d'afficher un nombre de commentaire par figure et par page.
        $commentaireAffichage = $commentairesRepo->nombreCommentaire($firstResultId, 2, $trickId);
        
        return $this->render('commentaires/blockCommentaire.html.twig', ['commentaireAffichage' => $commentaireAffichage]);
    }
     
    /**
     * @Route("/new", name="commentaires_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $orm = $this->getDoctrine()->getManager();
            $orm->persist($commentaire);
            $orm->flush();

            return $this->redirectToRoute('commentaires_index');
        }

        return $this->render(
            'commentaires/new.html.twig',
            [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
            ]
        );
    }
    

    /**
     * @Route("/{id}", name="commentaires_show", methods="GET")
     */
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('commentaires/show.html.twig', ['commentaire' => $commentaire]);
    }

    /**
     * @Route("/{id}/edit", name="commentaires_edit", methods="GET|POST")
     */
    public function edit(Request $request, Commentaires $commentaire): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaires_index', ['id' => $commentaire->getId()]);
        }

        return $this->render(
            'commentaires/edit.html.twig',
            [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="commentaires_delete", methods="DELETE")
     */
    public function delete(Commentaires $commentaire): Response
    {
        $orm = $this->getDoctrine()->getManager();
        $orm->remove($commentaire);
        $orm->flush();
        

        return $this->redirectToRoute('commentaires_index');
    }
}
