<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Form\EquipeType;
use App\Repository\EquipeRepository;
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
    public function index(EquipeRepository $repoEquipe): Response
    {
        $equipe = new Equipe();
        $equipeForm = $this->createForm(EquipeType::class, $equipe);
        return $this->render('index.html.twig', [
            'equipeForm' => $equipeForm->createView(),
            'equipes' => $repoEquipe->findAll(),
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
}
