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

    public static function normalizeComments($obj)
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new DateTimeNormalizer('Y-m-d H:i:s'), new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->normalize($obj, null, [
            'attributes' => [
                'id',
                'commentText',
                'createdAt',
                'user' => [
                    'name',
                    'surname'
                ]
            ]]);
    }
    
    public static function generateUniqueFileName()
    {
        return md5(uniqid());
    }
    
    public static function fetchLocationByAddress(string $address): array
    {
        $key = getenv('MAP_API_KEY');
        $queryUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $key;

        $arrContextOptions=array(
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        );

        $dataJson = file_get_contents($queryUrl, false, stream_context_create($arrContextOptions));
        $data = json_decode($dataJson, true);
        if (isset($data)) {
            return self::parseLocation($data);
        }
        return [];
    }
    
    private static function parseLocation($data): array
    {
        $street = $city = $postcode = $lat = $lng = null;
        $results = $data['results'];
        if (isset($results[0])) {
            $results = $results[0];
            $location = $results['geometry']['location'];
            $lat = $location['lat'];
            $lng = $location['lng'];
            $address_components = array_values($results['address_components']);
            foreach ($address_components as $component) {
                if (in_array('postal_code', $component['types'])) {
                    $postcode = $component['short_name'];
                }
                if (in_array('route', $component['types'])) {
                    $street = $component['short_name'];
                }
                if (in_array('locality', $component['types'])) {
                    $city = $component['short_name'];
                }
            }
            if (!isset($postcode)) {
                $postcode = null;
            }
        }
        return ['lat' => $lat, 'lng' => $lng, 'postcode' => $postcode, 'street' => $street, 'city' => $city];
    }

    /**
     * @param string $dataFile
     * @return array
     */
    public static function getData(string $dataFile): array
    {
        $dataArray = [];
        $handler = fopen($dataFile, 'r');
        while (($data = fgetcsv($handler, 5000, ';')) !== FALSE) {
            $dataArray[] = $data;
        }
        return $dataArray;
    }
}