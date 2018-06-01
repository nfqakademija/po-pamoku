<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Comment;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Form\Type\RatingType;
use App\Repository\CommentRepository;
use Symfony\Component\Form\Form;
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
        $activityRepository = $this->getDoctrine()->getRepository(Activity::class);
        $activity = $activityRepository->find($id);
        
        $form = $this->createForm(CommentType::class);
        $user = $this->getUser();
        $ratingrepo = $this->getDoctrine()->getRepository(Rating::class);
        $ratingFound = $ratingrepo->findOneBy(['user' => $user, 'activity' => $activity]);
        if ($ratingFound) {
            $ratingForm = $this->createForm(RatingType::class, $ratingFound);
        } else {
            $ratingForm = $this->createForm(RatingType::class);
        }

        if ($request->request->has('comment')) {
            $form->handleRequest($request);
            $response = $this->commentFormAction($form, $id);
        }

        if ($request->request->has('rating')) {
            $ratingForm->handleRequest($request);

            if ($ratingForm->isSubmitted() && $ratingForm->isValid()) {
                $rating = $ratingForm->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($rating);
                $em->flush();
            }
        }
        
        $city = $activity->getLocation()->getCity()->getId();
        $category = $activity->getSubcategory()->getCategory()->getId();
        $criteria = ["city" => $city, "category" => $category, 'id' => $id];
        $activities = $activityRepository->fetchFilteredData($criteria, ["rating" => "DESC"]);
        $similar = array_slice($activities, 0, 3);

        if (!isset($response)) {
            return $this->render('activity/show.html.twig', [
                'activity' => $activity,
                'similar' => $similar,
                'form' => $form->createView(),
                'post' => $this->userCanPostComments($id),
                'rate' => $this->userDidRate($id),
                'ratingForm' => $ratingForm->createView()
            ]);
        }

        return $response;
    }
    
    public function userCanPostComments($id)
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $repo = $this->getDoctrine()->getRepository(Comment::class)->findAllPastDay($user, $id);
            if (!$repo) {
                return true;
            }
        }
        return false;
    }

    public function userDidRate($id)
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $repo = $this->getDoctrine()->getRepository(Rating::class)->findOneBy(['activity' => $id, 'user' => $user->getId()]);
            if (!$repo) {
                return false;
            }
        }
        return true;

    }

    public function commentFormAction(Form $form, $id)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            $html = $this->renderView('activity/_commentForm.html.twig', [
                'form' => $form->createView(),
                'post' => $this->userCanPostComments($id)
            ]);

            return new Response($html, 200);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $html =  $this->renderView('activity/_commentForm.html.twig', [
                'form' => $form->createView(),
                'post' => $this->userCanPostComments($id)
            ]);

            return new Response($html, 400);
        }

        return;
    }

}