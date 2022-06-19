<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Repository\AdherentRepository;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdherentController extends AbstractController
{
    /**
     * @Route("/adherent", name="list_adherents")
     */
    public function index(AdherentRepository $repo): Response
    {
        $adherents = $repo->findAll();
        return $this->render('adherent/index.html.twig', [
            'controller_name' => 'AdherentController',
            'adherents' => $adherents
        ]);
    }

    /**
     * @Route("/adherent/new", name="add_adherent")
     * @Route("/adherent/edit/{id}", name="edit_adherent")
     */
    public function adherentForm(
        Adherent $adherent = null,
        EntityManagerInterface $emf,
        Request $request,
        AssociationRepository $repo
    ): Response {
        if (!$adherent) {
            $adherent = new Adherent();
        }
        $form = $this->createForm(AdherentType::class, $adherent);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$adherent->getId()) {
                $association = $repo->findOneById(1);
                $adherent->setAssociation($association);
            }
            $emf->persist($adherent);
            $emf->flush();
            return $this->redirectToRoute('list_adherents');
        }
        return $this->render('adherent/form_adherent.html.twig', [
            'adherentForm' => $form->createView(),
            'editMode' => $adherent->getId() !== null
        ]);
    }

    /**
     * @Route("/adherent/delete/{id}", name="delete_adherent")
     */
    public function deleteBureau(Adherent $adherent, EntityManagerInterface $emf): Response
    {
        foreach ($adherent->getMissions() as $mission) {
            $adherent->removeMission($mission);
        }
        $emf->remove($adherent);
        $emf->flush();
        return $this->redirectToRoute('list_adherents');
    }
}
