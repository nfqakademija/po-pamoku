<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Form\Type\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ActivityController extends Controller
{
    
    /**
     * @Route("/", name="activity_list")
     * @Method({"GET"})
     */
    public function index()
    {
        return $this->render('activity/index.html.twig');
    }
    
    
    /**
     * @Route("/activity/{id}", name="activity_show")
     */
    public function show($id, Request $request)
    {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $html = $this->renderView('activity/_commentForm.html.twig', [
                'form' => $form->createView()
            ]);

            return new Response($html, 300);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $html =  $this->renderView('activity/_commentForm.html.twig', [
                'form' => $form->createView()
            ]);

            return new Response($html, 400);
        }

        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/map", name="activity_map")
     * @Method({"GET"})
     */
    public function map()
    {
        return $this->render('map/map.html.twig');
    }
}