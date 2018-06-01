<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Comment;
use App\Entity\Rating;
use App\Entity\User;
use App\Form\Type\CommentType;
use App\Form\Type\RatingType;
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
        
        $user = $this->getUser();
        $form = $this->createForm(CommentType::class);
        $ratingForm = $this->getRatingForm($user, $activity);

        if ($request->request->has('comment')) {
            $form->handleRequest($request);
            $view = 'activity/_commentForm.html.twig';
            $parameters = [
                'form' => $form->createView()
            ];

            $response = $this->formAction($form, $view, $parameters, $id);
        }

        if ($request->request->has('rating')) {
            $ratingForm->handleRequest($request);
            $view = 'activity/_ratingForm.html.twig';
            $parameters = [
                'activity' => $activity,
                'ratingForm' => $ratingForm->createView(),
                'rate' => $this->userDidRate($id)
            ];

            $response = $this->formAction($ratingForm, $view, $parameters, $id);
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

    private function formAction(Form $form, $view, $parameters, $id) {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            $parameters['post'] = $this->userCanPostComments($id);
            $html = $this->renderView($view, $parameters);

            return new Response($html, 200);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $parameters['post'] = $this->userCanPostComments($id);
            $html =  $this->renderView($view, $parameters);

            return new Response($html, 400);
        }

        return;
    }

    private function userDidRate($id)
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $rating = $this
                ->getDoctrine()
                ->getRepository(Rating::class)
                ->findOneBy(['activity' => $id, 'user' => $user->getId()]);
            if (!$rating) {
                return false;
            }
        }
        return true;
    }

    private function userCanPostComments($id)
    {
        $user = $this->getUser();
        if ($user instanceof User) {
            $comments = $this
                ->getDoctrine()
                ->getRepository(Comment::class)
                ->findAllPastDay($user, $id);
            if (!$comments) {
                return true;
            }
        }
        return false;
    }

    private function getRatingForm(User $user, Activity $activity) {
        $ratingrepo = $this->getDoctrine()->getRepository(Rating::class);
        $ratingFound = $ratingrepo->findOneBy(['user' => $user, 'activity' => $activity]);
        if ($ratingFound) {
            $ratingForm = $this->createForm(RatingType::class, $ratingFound);
        } else {
            $ratingForm = $this->createForm(RatingType::class);
        }

        return $ratingForm;
    }
}