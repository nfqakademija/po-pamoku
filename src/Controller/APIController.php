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
     * @Route("/api/activity/{page}/{limit}", defaults={"page"=1, "limit"=10}, name="api_activity_list")
     * @Method({"GET"})
     */
    public function index(Request $request, $page, $limit) {

        $activityArr = array();
        $where = array("search" => '');
        $offset = ($page - 1) * $limit;

        $search = htmlspecialchars($request->get("search"));
        if($search){
            $where["search"] = $search;
        }

        $activities = $this->getDoctrine()->getRepository(Activity::class)->filter($where, array('id' => 'DESC'),$limit ,$offset);
        foreach ($activities as &$activity){
            $activity["timeFrom"] = $activity["timeFrom"]->format("H:i");
            $activity["timeTo"] = $activity["timeTo"]->format("H:i");
        }

        return new JsonResponse($activities);
    }
}