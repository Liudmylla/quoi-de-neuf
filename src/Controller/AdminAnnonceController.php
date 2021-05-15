<?php

namespace App\Controller;


use App\Entity\Annonce;
use App\Repository\CategoryRepository;
use App\Repository\AdminAnnonceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminAnnonceController extends AbstractController
{
    /**
     * @Route("/admin/annonce", name="admin_annonce")
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

}
