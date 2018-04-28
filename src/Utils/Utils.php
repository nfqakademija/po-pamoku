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
}