<?php
namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Location;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\Type\ActivityType;

class ActivityController extends Controller {

    /**
     * @Route("/", name="activity_list")
     * @Method({"GET"})
     */
    public function index(Request $request) {

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM App:Activity a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6)
        );

        return $this->render('activity/index.html.twig', array('activities' => $pagination));
    }

    /**
     * @Route("/activity/edit/{id}", name="activity_club")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userData);
            $entityManager->flush();
            return $this->redirectToRoute('activity_list');
        }
        return $this->render('activity/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/activity/{id}", name="activity_show")
     */
    public function show($id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        return $this->render('activity/show.html.twig', array('activity' => $activity));
    }
}