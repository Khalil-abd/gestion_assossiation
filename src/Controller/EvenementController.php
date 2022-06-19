<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Mission;
use App\Form\EvenementType;
use App\Form\MissionType;
use App\Repository\AssociationRepository;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EvenementController extends AbstractController
{
    /**
     * @Route("/evenement", name="list_evenements")
     */
    public function index(EvenementRepository $repo): Response
    {
        $evenements = $repo->findAll();
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
            'evenements' => $evenements
        ]);
    }

    /**
     * @Route("/evenement/{id}/mission", name="add_mission")
     * @Route("/evenement/{id}/mission/{mission_id}", name="edit_mission")
     * @ParamConverter("evenement", options={"mapping": {"id": "id"}})
     * @ParamConverter("mission", options={"mapping": {"mission_id": "id"}})
     */

    public function missionForm(
        Evenement $evenement,
        Mission $mission = null,
        EntityManagerInterface $emf,
        Request $request
    ): Response {
        if (!$mission) {
            $mission = new Mission();
        }
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$mission->getId()) {
                $mission->setEvenement($evenement);
            }
            $emf->persist($mission);
            $emf->flush();
            return $this->redirectToRoute('show_evenement', ['id' => $evenement->getId()]);
        }
        return $this->render('evenement/form_mission.html.twig', [
            'missionForm' => $form->createView(),
            'editMode' => $mission->getId() !== null
        ]);
    }

    /**
     * @Route("/evenement/new", name="add_evenement")
     * @Route("/evenement/edit/{id}", name="edit_evenement")
     */
    public function evenementForm(
        Evenement $evenement = null,
        EntityManagerInterface $emf,
        Request $request,
        AssociationRepository $repo
    ): Response {
        if (!$evenement) {
            $evenement = new Evenement();
        }
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$evenement->getId()) {
                $association = $repo->findOneById(1);
                $evenement->setAssociation($association);
            }
            $emf->persist($evenement);
            $emf->flush();
            return $this->redirectToRoute('list_evenements');
        }
        return $this->render('evenement/form_evenement.html.twig', [
            'evenementForm' => $form->createView(),
            'editMode' => $evenement->getId() !== null
        ]);
    }

    /**
     * @Route("/evenement/{id}", name="show_evenement")
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }


    /**
     * @Route("/evenement/delete/{id}", name="delete_evenement")
     */
    public function deleteEvenement(Evenement $evenement, EntityManagerInterface $emf): Response
    {
        $emf->remove($evenement);
        $emf->flush();
        return $this->redirectToRoute('list_evenements');
    }

    /**
     * @Route("/evenement/mission/delete/{id}", name="delete_mission")
     */
    public function deleteMission(Mission $mission, EntityManagerInterface $emf): Response
    {
        $id = $mission->getEvenement()->getId();
        $emf->remove($mission);
        $emf->flush();
        return $this->redirectToRoute('show_evenement', ['id' => $id]);
    }
}
