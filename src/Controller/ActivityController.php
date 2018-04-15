<?php
namespace App\Controller;
use App\Entity\Activity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


class ActivityController extends Controller {

    /**
     * @Route("/", name="activity_list")
     * @Method({"GET"})
     */
    public function index() {
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        return $this->render('activity/index.html.twig', array('activities' => $activities));
    }
    /**
     * @Route("/api/activities", name="activities_json")
     */
     public function getJson() {
        $activities = $this->getDoctrine()->getRepository(Activity::class)->createQueryBuilder('e')->select('e')->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return new JsonResponse($activities);
    }

    /**
     * @Route("/api/activity/{id}", name="activity_id_json")
     */
    public function getIdJson($id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->createQueryBuilder('e')->select('e')->where('e.id = :id')->setParameter('id', $id)->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return new JsonResponse($activity);
    }


    /**
     * @Route("/activity/edit/{id}", name="activity_club")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        //$test = $this->getDoctrine()->getRepository(Activity::class)->findBySomething($id);
        //var_dump($test);
        return $this->render('activity/edit.html.twig', array());
    }
    /**
     * @Route("/activity/{id}", name="activity_show")
     */
    public function show($id) {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        return $this->render('activity/show.html.twig', array('id' => $id));
    }
}