<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\SearchAnnonceFormType;
use App\Repository\AnnonceRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface; 
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, PaginatorInterface $paginator,
     CategoryRepository $categoryRepository,
     AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(SearchAnnonceFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $donnees =$annonceRepository->findLikeTitle($search);
        } else {
            $donnees = $annonceRepository->findBy([], ['updatedAt' => 'DESC']);
        }

        $annonces = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );
        
        return $this->render('home/index.html.twig', [
            'annonces' => $annonces,
            'categories' => $categoryRepository->findBy([], ['id' => 'ASC']),
            'form' => $form->createView(),
        ]);
    }
      
}
