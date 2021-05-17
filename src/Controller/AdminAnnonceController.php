<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Form\AdminAnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

 /**
     * @Route("/admin", name="admin_")
     */
class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/annonce", name="annonce")
     */
    public function index(Request $request, PaginatorInterface $paginator, CategoryRepository $categoryRepository): Response
    {
        

        $annonces = $this->getDoctrine()
        ->getRepository(Annonce::class)
        ->findAll([],['isValidated' => 'false']);
    
        return $this->render('admin_annonce/index.html.twig', [
          
            'annonces' => $annonces,
            'categories' => $categoryRepository->findBy([], ['id' => 'ASC']),
        ]);
    }
      /**
     * @Route("/new", name="annonce_new", methods={"GET","POST"})
     */

    public function new(Request $request): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AdminAnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setCreatedDate(new \DateTime());
            $annonce->setUpdatedAt( new \DateTime());
            $currentUser = $this->getUser();
            $annonce->setAuteur($currentUser);
            $annonce->setIsValidated(true);
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success', 'Votre nouvelle vient d\'etre publié');
            return $this->redirectToRoute('admin_annonce');
        }

        return $this->render('admin_annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Annonce $annonce,CategoryRepository $categoryRepository): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'categories' => $categoryRepository->findBy([], ['id' => 'ASC'])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce, CategoryRepository $categoryRepository): Response

    {
        //if (!($this->getUser() == $annonce->getAuteur())) {

            // If not the owner, throws a 403 Access Denied exception

            //throw new AccessDeniedException('Only the owner can edit the annonce!');

       // }
      
        $form = $this->createForm(AdminAnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setUpdatedAt( new \DateTime());
            $entityManager->persist($annonce);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien modifié la nouvelle');

            return $this->redirectToRoute('admin_annonce');
        }

        return $this->render('admin_annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'categories'=>$categoryRepository->findBy([], ['id' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Annonce $annonce): Response

    {
       // if (!($this->getUser() == $annonce->getAuteur())) {

            // If not the owner, throws a 403 Access Denied exception

           // throw new AccessDeniedException('Only the owner can delete the annonce!');

      //  }
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }

}
