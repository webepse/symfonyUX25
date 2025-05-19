<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\PlayerTypeForm;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

final class AdminPlayerController extends AbstractController
{
    /**
    * Permet d'afficher l'ensemble des teams
    *
    * @param PaginationService $pagination
    * @param int $page
    * @return Response
    */
    #[Route('/admin/players/{page<\d+>?1}', name: 'admin_players_index')]
    public function index(PaginationService $pagination, int $page): Response
    {
        $pagination->setEntityClass(Player::class)
            ->setPage($page)
            ->setLimit(10);


        return $this->render('admin/player/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * Permet de créer un joueur
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/admin/players/create", name: "admin_players_create")]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $player = new Player();
        $form = $this->createForm(PlayerTypeForm::class, $player);
        $form->handleRequest($request);

        // partie traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($player);
            $manager->flush();

            $this->addFlash(
                'success',
                "Le joueur a été enregistré avec succés"
            );

            return $this->redirectToRoute("admin_players_index");
        }

        return $this->render("admin/player/create.html.twig",[
            'myForm' => $form->createView()
        ]);

    }

    /**
     * Permet de modifier un utilisateur
     * @param Player $player
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param int $id
     * @param int $page
     * @return Response
     */
    #[Route("/admin/players/{id}/edit/{page<\d+>?1}", name: "admin_players_edit")]
    public function edit(Player $player,Request $request, EntityManagerInterface $manager,int $id, int $page): Response
    {
        $form = $this->createForm(PlayerTypeForm::class, $player);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($player);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le joueur <strong>".$player->getLastName()." ".$player->getFirstName()."</strong> a bien été modifié"
            );
            return $this->redirectToRoute("admin_players_index", ['page' => $page]);
        }

        return $this->render("admin/player/edit.html.twig",[
            'player'=> $player,
            'myForm' => $form->createView(),
            'page' => $request->query->get('mypage')
        ]);
    }

    /**
     * Permet de supprimer un joueur
     * @param Request $request
     * @param Player $player
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/admin/players/{id}/delete", name: "admin_players_delete")]
    public function delete(Request $request, Player $player, EntityManagerInterface $manager): Response
    {
        $playerId = $player->getId();
        $playerName = $player->getfirstName()." ".$player->getLastName();
        $message = "Le joueur id:".$playerId." a bien été supprimé";
        $manager->remove($player);
        $manager->flush();
        if(TurboBundle::STREAM_FORMAT === $request->getPreferredFormat())
        {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render("admin/player/delete.html.twig",["playerId" => $playerId, "message" => $message]);
        }
        $this->addFlash(
            "success",
            $message
        );
        return $this->redirectToRoute('admin_players_index');
    }


}
