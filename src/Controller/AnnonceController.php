<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Category;
use App\Form\AnnonceType;
use Symfony\Component\Mime\Email;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/annonce")
 */
class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="annonce_index", methods={"GET"})
     */
    public function index(AnnonceRepository $annonceRepository,CategoryRepository $categoryRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
            'categories' => $categoryRepository->findBy([], ['id' => 'ASC'])
        ]);
    }

    /**
     * @Route("/new", name="annonce_new", methods={"GET","POST"})
     */
    public function new(Request $request,MailerInterface $mailer): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setCreatedDate(new \DateTime());
            $annonce->setUpdatedAt( new \DateTime());
            $currentUser = $this->getUser();
            $annonce->setAuteur($currentUser);
            $annonce->setIsValidated(false);
            $entityManager->persist($annonce);
            $entityManager->flush();
            $message = (new Email())
            ->from('from@example.com')
            ->to('to@example.com')
            ->subject('Une nouvelle à valider')
            ->text('Utilisateur a partagé une nouvelle');
            $mailer->send($message);
            $this->addFlash('success', 'Votre nouvelle va etre publié apres la verification de la contenu par un moderateur');
            return $this->redirectToRoute('home');
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_show", methods={"GET"})
     */
    public function show(Annonce $annonce,CategoryRepository $categoryRepository): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'categories' => $categoryRepository->findBy([], ['id' => 'ASC'])
        ]);
    }

    /**
     * @Route("/{id}/edit", name="annonce_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Annonce $annonce, CategoryRepository $categoryRepository, MailerInterface $mailer): Response

    {
        //if (!($this->getUser() == $annonce->getAuteur())) {

            // If not the owner, throws a 403 Access Denied exception

            //throw new AccessDeniedException('Only the owner can edit the annonce!');

       // }
     
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $annonce->setUpdatedAt( new \DateTime());
            $annonce->setIsValidated(false);
            $entityManager->persist($annonce);
            $entityManager->flush();
            $message = (new Email())
            ->from('from@example.com')
            ->to('to@example.com')
            ->subject('Une nouvelle à valider')
            ->text('Utilisateur a modifié une nouvelle');
            $mailer->send($message);
            $this->addFlash('success', 'Votre nouvelle a bien été modifié et  va etre publié apres la verification de la contenu par un moderateur');

            return $this->redirectToRoute('home');
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form->createView(),
            'categories'=>$categoryRepository->findBy([], ['id' => 'ASC']),
        ]);
    }

    /**
     * @Route("/{id}", name="annonce_delete", methods={"POST"})
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

        return $this->redirectToRoute('annonce_index');
    }
}
