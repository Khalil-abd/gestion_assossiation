<?php

namespace App\Controller;

use App\Entity\Bureau;
use App\Entity\Membre;
use App\Form\BureauType;
use App\Form\MembreType;
use App\Repository\AssociationRepository;
use App\Repository\BureauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class BureauController extends AbstractController
{
    /**
     * @Route("/bureau", name="list_bureaux")
     */
    public function index(BureauRepository $repository): Response
    {
        $bureaux = $repository->findAll();
        return $this->render('bureau/index.html.twig', [
            'controller_name' => 'BureauController',
            'bureaux' => $bureaux,
        ]);
    }



    /**
     * @Route("/bureau/{id}/membre", name="add_membre")
     * @Route("/bureau/{id}/membre/{membre_id}", name="edit_membre")
     * @ParamConverter("bureau", options={"mapping": {"id": "id"}})
     * @ParamConverter("membre", options={"mapping": {"membre_id": "id"}})
     */
    public function membreForm(
        Bureau $bureau,
        Membre $membre = null,
        EntityManagerInterface $emf,
        Request $request
    ): Response {
        if (!$membre) {
            $membre = new Membre();
        }
        $form = $this->createForm(MembreType::class, $membre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$membre->getId()) {
                $membre->setBureau($bureau);
            }
            $emf->persist($membre);
            $emf->flush();
            return $this->redirectToRoute('show_bureau', ['id' => $bureau->getId()]);
        }
        return $this->render('bureau/form_membre.html.twig', [
            'membreForm' => $form->createView(),
            'editMode' => $membre->getId() !== null
        ]);
    }

    /**
     * @Route("/bureau/new", name="add_bureau")
     * @Route("/bureau/edit/{id}", name="edit_bureau")
     */
    public function bureauForm(
        Bureau $bureau = null,
        EntityManagerInterface $emf,
        Request $request,
        AssociationRepository $repo
    ): Response {
        if (!$bureau) {
            $bureau = new Bureau();
        }
        $form = $this->createForm(BureauType::class, $bureau);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$bureau->getId()) {
                $association = $repo->findOneById(1);
                $bureau->setAssociation($association);
            }
            $emf->persist($bureau);
            $emf->flush();
            return $this->redirectToRoute('list_bureaux');
        }
        return $this->render('bureau/form_bureau.html.twig', [
            'bureauForm' => $form->createView(),
            'editMode' => $bureau->getId() !== null
        ]);
    }


    /**
     * @Route("/bureau/membre/delete/{id}", name="delete_membre")
     */
    public function deleteMembre(Membre $membre, EntityManagerInterface $emf): Response
    {
        $id = $membre->getBureau()->getId();
        $emf->remove($membre);
        $emf->flush();
        return $this->redirectToRoute('show_bureau', ['id' => $id]);
    }

    /**
     * @Route("/bureau/delete/{id}", name="delete_bureau")
     */
    public function deleteBureau(Bureau $bureau, EntityManagerInterface $emf): Response
    {
        $emf->remove($bureau);
        $emf->flush();
        return $this->redirectToRoute('list_bureaux');
    }

    /**
     * @Route("/bureau/{id}", name="show_bureau")
     */
    public function show(Bureau $bureau): Response
    {
        return $this->render('bureau/show.html.twig', [
            'bureau' => $bureau,
        ]);
    }


    /**
     * @Route("/", name="app_home")
     */
    public function home(AssociationRepository $repo): Response
    {
        $association = $repo->find(1);
        return $this->render('bureau/home.html.twig', [
            'controller_name' => 'BureauController',
            'association' => $association
        ]);
    }
}
