<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Form\TricksType;
use App\Form\TricksEditType;
use App\Form\EditImageType;
use App\Form\VideoType;
use App\Repository\TricksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Form\UserShowType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Paginator;
use App\Form\PaginatorType;
use App\Repository\PaginatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TricksController extends AbstractController
{
    /**
     * @Route("/", name="tricks_index", methods="GET")
     */
    public function index(TricksRepository $tricksRepository): Response
    {
        return $this->render('tricks/index.html.twig', ['tricks' => $tricksRepository->nombreTrick(0, 4)]);
    }

    /**
     * @Route("/trick/ajax", name="trick_ajax", methods="GET|POST")
     */
    public function ajaxAction(Request $request, TricksRepository $tricksRepository)
    {
        $id = $request->request->get('id');
        $tricks = $tricksRepository->nombreTrick($id, 4);
        return $this->render('tricks/blockTrick.html.twig', ['tricks' => $tricks]);
        return new JsonResponse($tricks);
    }

    public function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * @Route("/new/trick", name="tricks_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $trick = new Tricks();
        
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $trick->getImage();
            $fil = $trick->getSecondeImage();
            
            
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $fileNam = $this->generateUniqueFileName().'.'.$fil->guessExtension();

            try {
                $file->move($this->getParameter('images_directory'), $fileName, $fileNam);
                    
                $trick->setImage($fileName);
                $trick->setSecondeImage($fileNam);
                $em = $this->getDoctrine()->getManager();
                $date = new \DateTime();
                $trick->setDateCreation($date->format("d-m-Y h:i"));
                $trick->addAuteur($this->getUser());
                $trick->setSlug($this->slugify($trick->getName()));

                $em->persist($trick);
                $em->flush();
                $this->addFlash('success', 'Votre figure à bien été enregistré.');
            } catch (FileException $e) {
                $this->addFlash('error', "La figure n'a pas pu être enregistré.");
            }

            

            return $this->redirectToRoute('tricks_index');
        }

        return $this->render(
            'tricks/new.html.twig', [
            'trick' => $trick,
            'user' => $user,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @Route("/{slug}/{page}/show/one/trick", name="tricks_show", methods="GET|POST"
     */
    public function show(Tricks $trick, Request $request, CommentairesRepository $CommentairesRepository, $page, Paginator $pagina): Response
    {
        $user = $this->getUser();
        $commentaires = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaires);
        $form->handleRequest($request);
        $nombreMaxParPage = 2;
        $nombreMax = 2;
        $firstResult = ($page-1) * $nombreMaxParPage;
        $commentaireAffichage = $CommentairesRepository->nombreCommentaire($firstResult, $nombreMax, $trick->getId());

        
        $commentairePagination = $CommentairesRepository->paginationCommentaire($page, $nombreMaxParPage, $trick->getId());
        
        $pagination = array(
            'page' => $page,
            'nbPages' => ceil(count($commentairePagination) / $nombreMaxParPage),
            'nomRoute' => 'tricks_show',
            'paramsRoute' => array('id' => $trick->getId())
        );


        
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $commentaires->setDateCommentaire($date->format("d-m-Y H:i"));
            $user->addCommentaireId($commentaires);
            $trick->addCommentairesId($commentaires);
            $pagina->addCommentaireId($commentaires);

            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($trick, $user, $commentaires);
                $em->flush();
                $this->addFlash('success', 'Votre commentaire à bien été envoyé !!!');
            } catch (FileException $e) {
                $this->addFlash('error', "Le commentaire n'a pas pu être envoyé.");
            }
            
            return $this->redirectToRoute('tricks_index', ['id' => $trick->getId()]);
        }
        
        
        return $this->render('tricks/show.html.twig', ['trick' => $trick, 'form' => $form->createView(),'commentaireAffichage' => $commentaireAffichage, 'commentairePagination' => $commentairePagination,'user' => $user,'pagination' => $pagination]);
    }

    /**
     * @Route("/{slug}/edit/trick", name="tricks_edit", methods="GET|POST")
     */
    public function edit(Request $request, Tricks $trick): Response
    {
        $form = $this->createForm(TricksEditType::class, $trick);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            $trick->setDateModification($date->format("d-m-Y H:i"));
            
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($trick);
                $em->flush();
                $this->addFlash('success', 'Votre figure à bien été modifié !!!');
            } catch (FileException $e) {
                $this->addFlash('error', "La figure n'a pas pu être modifié.");
            }
            
            return $this->redirectToRoute('tricks_index', ['id' => $trick->getId()]);
        }

        return $this->render(
            'tricks/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{slug}/{numberImage}/editImage", name="edit_image", methods="GET|POST")
     */
    public function editImage(Request $request, Tricks $trick, $numberImage): Response
    {
        $field="image";
        $method="setImage";
        $getter="getImage";
        if ($numberImage== 2) {
            $field="secondeImage";
            $method="setSecondeImage";
            $getter="getSecondeImage";
        }

        $trick->$method(
            new File($this->getParameter('images_directory').'/'.$trick->$getter())
        );
        
        $form = $this->createForm(EditImageType::class, $trick, ["image"=>$field]);
        
        $form->handleRequest($request);

        
        if ($form->isSubmitted()) {
            if ($numberImage== 2) {
                $file = $trick->getSecondeImage();
            } else {
                $file = $trick->getImage();
            }
            
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $date = new \DateTime();
            $trick->setDateModification($date->format("d-m-Y H:i"));
            
            try {
                $file->move($this->getParameter('images_directory'), $fileName);
                if ($file == $trick->getSecondeImage()) {
                    $trick->setSecondeImage($fileName);
                } else {
                    $trick->setImage($fileName);
                }
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($trick);
                $em->flush();
                $this->addFlash('success', 'Votre image à bien été modifié !!!');
            } catch (FileException $e) {
                $this->addFlash('error', "L'image n'a pas pu être modifié.");
            }
            
            return $this->redirectToRoute('tricks_index', ['id' => $trick->getId()]);
        }

        return $this->render(
            'tricks/editImage.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'numberImage'=>$numberImage,
            ]
        );
    }

    /**
     * @Route("/{slug}/{numberVideo}/editVideo", name="tricks_video", methods="GET|POST")
     */
    public function TricksVideo(Request $request, Tricks $trick, $numberVideo): Response
    {
        $entityField="video";
        if ($numberVideo== 2) {
            $entityField="secondeVideo";
        }
        if ($numberVideo== 3) {
            $entityField="troisiemeVideo";
        }
        $form = $this->createForm(VideoType::class, $trick, ["video" =>$entityField]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $date = new \DateTime();
            $trick->setDateModification($date->format("d-m-Y H:i"));
            
            try {
                $em = $this->getDoctrine()->getManager();
                $em->persist($trick);
                $em->flush();
                $this->addFlash('success', 'Votre vidéo à bien été modifié !!!');
            } catch (FileException $e) {
                $this->addFlash('error', "La vidéo n'a pas pu être modifié.");
            }
            
            return $this->redirectToRoute('tricks_index', ['id' => $trick->getId()]);
        }

        return $this->render(
            'tricks/editVideo.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'numberVideo'=>$numberVideo,
            ]
        );
    }

    /**
     * @Route("/{id}/deleteTrick", name="tricks_delete", methods="DELETE")
     */
    public function delete(Request $request, Tricks $trick): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($trick);
        $em->flush();
        
        return $this->redirectToRoute('tricks_index');
    }
}
