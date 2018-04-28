<?php

namespace App\Controller;

use App\Entity\Activity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class APIController extends Controller
{
	
	
	/**
	 * @Route("/api/activity", name="api_activity_list")
	 * @Method({"GET"})
	 */
	public function index(Request $request)
	{
		$orderAttr = ["name", "priceFrom", "priceTo", "ageFrom", "ageTo"];
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
		$order = in_array($sort, ["asc", "desc"]) ? $sort : "ASC";
		$orderBy = in_array($sortBy, $orderAttr) ? [$sortBy => $order] : ['name' => $order];
		$offset = ($page - 1) * $limit;
		
		$activities = $this->getDoctrine()->getRepository(Activity::class)->fetchFilteredData($where, $orderBy,
			$limit, $offset);
		foreach ($activities as &$activity) {
			$activity["timeFrom"] = $activity["timeFrom"]->format("H:i");
			$activity["timeTo"] = $activity["timeTo"]->format("H:i");
		}
		
		return new JsonResponse($activities);
	}
	
	/**
	 * @Route("/api/activity/{id}", name="api_activity")
	 * @Method({"GET"})
	 */
	public function apiActivity($id)
	{
		$activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
		return new JsonResponse($this->activityObjToArray($activity));
	}
	
	private function activityObjToArray($activity)
	{
		$timetables = [];
		foreach ($activity->getTimetables() as $timetable) {
			$timetables[$timetable->getId()] = [
				"weekday" => $timetable->getWeekday()->getName(),
				"timeFrom" => $timetable->getTimeFrom()->format("H:i"),
				"timeTo" => $timetable->getTimeTo()->format("H:i"),
			];
		}
		return [
			"id" => $activity->getId(),
			"name" => $activity->getName(),
			"description" => $activity->getDescription(),
			"location" => [
				"city" => $activity->getLocation()->getCity()->getName(),
				"street" => $activity->getLocation()->getStreet(),
				"house" => $activity->getLocation()->getHouse(),
				"apartment" => $activity->getLocation()->getApartment(),
				"postcode" => $activity->getLocation()->getApartment(),
			],
			"priceFrom" => $activity->getpriceFrom(),
			"priceTo" => $activity->getpriceTo(),
			"ageFrom" => $activity->getAgeFrom(),
			"ageTo" => $activity->getAgeTo(),
			"pathToLogo" => $activity->getPathToLogo(),
			"subcategory" => $activity->getSubcategory()->getName(),
			"timetables" => $timetables,
		];
	}
}