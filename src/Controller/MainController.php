<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Personne;
use App\Form\EquipeType;
use App\Form\PersonneType;
use App\Repository\EquipeRepository;
use App\Repository\PersonneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EquipeRepository $repoEquipe, PersonneRepository $repoPersonne): Response
    {
        $equipe = new Equipe();
        $equipeForm = $this->createForm(EquipeType::class, $equipe);

        $personne = new Personne();
        $personneForm = $this->createForm(PersonneType::class, $personne);

        return $this->render('index.html.twig', [
            'equipeForm' => $equipeForm->createView(),
            'personneForm' => $personneForm->createView(),

            'equipes' => $repoEquipe->findAll(),
            'personnes' => $repoPersonne->findAll(),
        ]);
    }
    /**
     * @Route("/ajouter/equipe/", name="equipe_ajouter")
     */
    public function ajouterEquipe(Request $request, EntityManagerInterface $em): Response
    {
        //dd('route ajouter equipe');
        ///$em = $this->getDoctrine()->getManager();
        $equipe = new Equipe();
        $equipeForm = $this->createForm(EquipeType::class, $equipe);
        $equipeForm->handleRequest($request);
        if ($equipeForm->isSubmitted() && $equipeForm->isValid()) {
            $em->persist($equipe);
            $em->flush();
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/ajouter/personne/", name="personne_ajouter")
     */
    public function ajouterPersonne(Request $request, EntityManagerInterface $em): Response
    {
        //dd('route ajouter equipe');
        ///$em = $this->getDoctrine()->getManager();
        $personne = new Personne();
        $personneForm = $this->createForm(PersonneType::class, $personne);
        $personneForm->handleRequest($request);
        if ($personneForm->isSubmitted() && $personneForm->isValid()) {
            $equipe = $personneForm->get('equipes')->getData();
            if ($equipe)
                $personne->addEquipe($equipe);
            $em->persist($personne);
            $em->flush();
        }
        return $this->redirectToRoute('home');
    }
}
