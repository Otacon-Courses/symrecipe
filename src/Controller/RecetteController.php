<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    private $em;
    public function _construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /***
     *
     * This controller display all recipes
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param RecetteRepository $repository
     * @return Response
     */

    #[Route('/recette', name: 'recette.index', methods: ['GET', 'POST'])]
    public function index(PaginatorInterface $paginator, Request $request, RecetteRepository $repository): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('/pages/recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    /***
     * This controller allow us to add a new recipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */

    #[Route('/recette/creation', name: 'recette.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($recette);
                $this->addFlash('notice', 'la recette a bien été ajoutée');
            }
            $manager->flush();
        return $this->render('pages/recette/recette.new.html.twig', ['form' => $form->createView()]);
    }


    /**
     * This controller allow us to edit a recipe
     * @param Recette $recette
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route('/recette/edition.{id}', name: 'recette.edit', methods: ['GET', 'POST'])]
    public function edit(Recette $recette, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette = $form->getData();
            $manager->persist($recette);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Votre ingrédient a bien été mis à jour'
            );
            return $this->redirectToRoute('recette.new');

        }
        return $this->render('pages/recette/edition.html.twig', ['form' => $form->createView()]);

    }

    #[Route('/recette/suppression/{id}', name: 'recette.delete' , methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, Recette $recette, Request $request) : Response {
        $manager->remove($recette);
        $manager->flush();

        $this->addFlash('notice', 'La recette a bien été supprimé');
        return $this->redirectToRoute('recette.index');
    }

}
