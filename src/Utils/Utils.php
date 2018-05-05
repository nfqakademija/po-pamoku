<?php
/**
 * Created by PhpStorm.
 * User: DeimantasAbelskis
 * Date: 2018-04-28
 * Time: 14:10
 */

namespace App\Utils;

use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Utils
{
    
    public static function normalize($obj)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer("H:i"), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        
        return $serializer->normalize($obj);
    }
    
    public static function generateUniqueFileName()
    {
        return md5(uniqid());
    }
    
    public static function fetchLocationByAddress(string $address): array
    {
        $key = getenv('MAP_API_KEY');
        $queryUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.$key;
        $dataJson = @file_get_contents($queryUrl);
        $data = json_decode($dataJson, true);
        return self::parseLocation($data);
    }
    
    private static function parseLocation(array $data): array
    {
        $results = $data['results'][0];
        $location = $results['geometry']['location'];
        $lat = $location['lat'];
        $lng = $location['lng'];
        $address_components  = array_values($results['address_components']);
        $postcode = $address_components[6]['long_name'];
        
        return ['lat' => $lat, 'lng' => $lng, 'postcode' => $postcode];
    }
}