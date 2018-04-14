<?php
namespace App\Controller;

use App\Entity\Activity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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
     * @Route("/activity/{id}", name="activity_show")
     */
    public function show($id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        return $this->render('activity/show.html.twig', array('activity' => $activity));
    }
}