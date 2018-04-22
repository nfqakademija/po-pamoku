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
     * @Route("/api/activities", name="api_activity_list")
     * @Method({"GET"})
     */
    public function index(Request $request) {

        $orderAttr = array("name", "priceFrom", "priceTo", "ageFrom", "ageTo");
        $page = htmlspecialchars($request->get("page"));
        $sort = htmlspecialchars($request->get("sort"));
        $sortBy = htmlspecialchars($request->get("sortby"));
        $limit = htmlspecialchars($request->get("limit"));
        $where["search"] = htmlspecialchars($request->get("search"));
        $where["priceFrom"] = htmlspecialchars($request->get("priceFrom"));
        $where["priceTo"] = htmlspecialchars($request->get("priceTo"));
        $where["ageFrom"] = htmlspecialchars($request->get("ageFrom"));
        $where["ageTo"] = htmlspecialchars($request->get("ageTo"));
        $where["timeFrom"] = htmlspecialchars($request->get("timeFrom"));
        $where["timeTo"] = htmlspecialchars($request->get("timeTo"));
        $where["weekday"] = htmlspecialchars($request->get("weekday"));
        $where["city"] = htmlspecialchars($request->get("city"));
        $where["category"] = htmlspecialchars($request->get("category"));
        $where["subcategory"] = htmlspecialchars($request->get("subcategory"));

        $page = is_numeric($page) ? $page : 1;
        $limit = is_numeric($limit) ? $limit : 10;
        $order = in_array($sort, array("asc","desc")) ? $sort : "ASC";
        $orderBy = in_array($sortBy, $orderAttr) ? array($sortBy => $order) : array('name' => $order);
        $offset = ($page - 1) * $limit;

        $activities = $this->getDoctrine()->getRepository(Activity::class)->fetchFilteredData($where, $orderBy ,$limit ,$offset);
        foreach ($activities as &$activity){
            $activity["timeFrom"] = $activity["timeFrom"]->format("H:i");
            $activity["timeTo"] = $activity["timeTo"]->format("H:i");
        }

        return new JsonResponse($activities);
    }
}