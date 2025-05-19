<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamTypeForm;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminTeamController extends AbstractController
{
    /**
     * Permet d'afficher l'ensemble des équipes
     *
     * @param PaginationService $pagination
     * @param int $page
     * @return Response
     */
    #[Route('/admin/teams/{page<\d+>?1}', name: 'admin_teams_index')]
    public function index(PaginationService $pagination, int $page): Response
    {
        $pagination->setEntityClass(Team::class)
            ->setPage($page)
            ->setLimit(10);


        return $this->render('admin/team/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet d'ajouter une équipe
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/admin/teams/create", name: "admin_teams_create")]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $team = new Team();
        $form = $this->createForm(TeamTypeForm::class, $team);
        $form->handleRequest($request);

        // partie traitement du formulaire
        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($team);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'équipe a été enregistrée avec succés"
            );

            return $this->redirectToRoute("admin_teams_index");
        }

        return $this->render("admin/team/create.html.twig",[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une équipe
     * @return Response
     */
    #[Route("/admin/teams/{id}/edit", name: "admin_teams_edit")]
    public function edit(): Response
    {
        return $this->render("admin/team/edit.html.twig",[

        ]);
    }

    /**
     * Permet de supprimer une équipe
     * @param Team $team
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/admin/teams/{id}/delete", name: "admin_teams_delete")]
    public function delete(Team $team, EntityManagerInterface $manager): Response
    {
        if(count($team->getPlayers())>0)
        {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'équipe {$team->getName()} car elle comporte des joueurs"
            );
        }else{
            $this->addFlash(
                'success',
                "L'équipe <strong>{$team->getName()}</strong> a bien été supprimée"
            );
            $manager->remove($team);
            $manager->flush();
        }


        return $this->redirectToRoute('admin_teams_index');
    }
}
