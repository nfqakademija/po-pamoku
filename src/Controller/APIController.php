<?php
namespace App\Controller;

use App\Entity\Activity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class APIController extends Controller {


    /**
     * @Route("/api/activity/{id}", name="api_activity")
     * @Method({"GET"})
     */
    public function apiActivity($id) {

        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);

        return new JsonResponse($this->activityObjToArray($activity));
    }

    /**
     * @Route("/api/activity/{page}/{limit}", name="api_activity_list")
     * @Method({"GET"})
     */
    public function index($page, $limit) {

        $activityArr = array();
        $offset = ($page - 1) * $limit;
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findBy(array(),array('name' => 'ASC'),$limit ,$offset);

        foreach ($activities as $activity){
            $activityArr[$activity->getId()] = $this->activityObjToArray($activity);
        }

        return new JsonResponse($activityArr);
    }

    private function activityObjToArray($activity){
        $timetables = array();
        foreach ($activity->getTimetables() as $timetable){
            $timetables[$timetable->getId()] = array(
                "weekday" => $timetable->getWeekday()->getName(),
                "timeFrom" => $timetable->getTimeFrom()->format("H:i"),
                "timeTo" => $timetable->getTimeTo()->format("H:i"),
            );
        }
        return array(
            "name" => $activity->getName(),
            "description" => $activity->getDescription(),
            "location" => array(
                "city" => $activity->getLocation()->getCity()->getName(),
                "street" => $activity->getLocation()->getStreet(),
                "house" => $activity->getLocation()->getHouse(),
                "apartment" => $activity->getLocation()->getApartment(),
                "postcode" => $activity->getLocation()->getApartment(),
            ),
            "priceFrom" => $activity->getpriceFrom(),
            "priceTo" => $activity->getpriceTo(),
            "ageFrom" => $activity->getAgeFrom(),
            "ageTo" => $activity->getAgeTo(),
            "pathToLogo" => $activity->getPathToLogo(),
            "subcategory" => $activity->getSubcategory()->getName(),
            "timetables" => $timetables

        );
    }
}