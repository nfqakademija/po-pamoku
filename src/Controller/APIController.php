<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Subcategory;
use App\Utils\Utils;
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
     * @Method({"GET", "POST"})
     */
    public function index(Request $request)
    {
        $orderAttr = ["name", "priceFrom", "priceTo", "ageFrom", "ageTo"];
        $paramsAttr = [
            "page",
            "sort",
            "sortBy",
            "limit",
            "search",
            "priceFrom",
            "priceTo",
            "ageFrom",
            "ageTo",
            "city",
            "street",
            "category",
            "subcategory",
            "timeFrom",
            "timeTo",
            "weekday",
        ];
        
        $params = $request->query->all();
        
        foreach ($paramsAttr as $value) {
            $params[$value] = isset($params[$value]) ? htmlspecialchars($params[$value]) : '';
        }
        
        $page = $params["page"];
        $sort = $params["sort"];
        $sortBy = $params["sortBy"];
        $limit = $params["limit"];
        
        $page = is_numeric($page) ? $page : 1;
        $limit = is_numeric($limit) ? $limit : 10;
        $order = in_array($sort, ["asc", "desc"]) ? $sort : "ASC";
        $orderBy = in_array($sortBy, $orderAttr) ? [$sortBy => $order] : ['name' => $order];
        $offset = ($page - 1) * $limit;
        
        $activities = $this->getDoctrine()->getRepository(Activity::class)
            ->fetchFilteredData($params, $orderBy);
        
        $count = count($activities);
        $activities = array_slice($activities, $offset, $limit);
        
        foreach ($activities as &$activity) {
            $activity["timeFrom"] = $activity["timeFrom"]->format("H:i");
            $activity["timeTo"] = $activity["timeTo"]->format("H:i");
        }
        
        return new JsonResponse(['count' => $count, 'Activities' => $activities]);
    }
    
    /**
     * @Route("/api/filter/city", name="api_filtters_city")
     * @Method({"GET"})
     */
    public function cityFilters()
    {
        $cities = $this->getDoctrine()->getRepository(City::class)->findBy([], ["name" => "ASC"]);
        
        return new JsonResponse(Utils::normalize($cities));
    }
    
    /**
     * @Route("/api/filter/category", name="api_filtters_category")
     * @Method({"GET"})
     */
    public function categoryFilters()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy([], ["name" => "ASC"]);
        
        return new JsonResponse(Utils::normalize($categories));
    }
    
    /**
     * @Route("/api/filter/subcategory/{category}", name="api_filtters_subcategory")
     * @Method({"GET"})
     */
    public function subcategoryFilters($category)
    {
        $subcategories = $this->getDoctrine()->getRepository(Subcategory::class)->findBy(['category' => $category],
            ["name" => "ASC"]);
        
        return new JsonResponse(Utils::normalize($subcategories));
    }
    
    /**
     * @Route("/api/activity/{id}", name="api_activity")
     * @Method({"GET"})
     */
    public function apiActivity($id)
    {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($id);
        
        return new JsonResponse(Utils::normalize($activity));
    }
    
    
}