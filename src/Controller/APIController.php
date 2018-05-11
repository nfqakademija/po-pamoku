<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\City;
use App\Entity\Comment;
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
            "price",
            "age",
            "city",
            "category",
            "subcategory",
            "time",
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

        
        return new JsonResponse(['count' => $count, 'Activities' => $activities]);
    }
    
    /**
     * @Route("/api/filter/init", name="api_filtters_defaults")
     * @Method({"GET"})
     */
    public function initFilters()
    {
        $cities = $this->getDoctrine()->getRepository(City::class)->findBy([], ["name" => "ASC"]);
        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy([], ["name" => "ASC"]);
        $weekdays = [
            "Pirmadienis",
            "Antradienis",
            "Trečiadienis",
            "Ketvirtadienis",
            "Penktadienis",
            "Šeštadienis",
            "Sekmadienis",
        ];
        $mins = ["00", "30"];
        $times = [];
        for ($i = 6; $i < 22; $i++) {
            for ($j = 0; $j < count($mins); $j++) {
                $times[] = $i . ":" . $mins[$j];
            }
        }
        
        return new JsonResponse([
            'cities' => Utils::normalize($cities),
            'categories' => Utils::normalize($categories),
            'times' => $times,
            'weekdays' => $weekdays,
        ]);
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

    /**
     * @Route("/api/comments/{id}", name="api_comments")
     * @Method({"GET"})
     */
    public function apiComments($id)
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['activity'=>$id]);
        return new JsonResponse(Utils::normalizeComments($comments));
    }

}