<?php
/**
 * Created by PhpStorm.
 * User: utilisateur
 * Date: 05/03/17
 * Time: 13:49
 */

namespace AppBundle\Service;


class ImageService
{
    public function __construct()
    {
    }

    public function getImageNature($countImgs)
    {

        if ($countImgs / 8 <= 1) {
            $loops = 1;
        } elseif ($countImgs % 8 == 0) {
            $loops = $countImgs / 8;
        } else {
            $loops = ($countImgs / 8) + 1;
        }
        $count = 0;

        $rdyImages = array();
        $imagesRestantes = $countImgs;

        for ($i = $loops; $i != 0; $i--) {
            $url = 'http://www.naturepicoftheday.com/random';
            $html = file_get_contents($url);
            $crawler = new Crawler($html);
            $images = $crawler
                ->filterXpath('//span[contains(@class, "PLAIN_LNK")]')
                ->filterXpath('//img')
                ->extract(array('src'));

            foreach ($images as $image) {
                if ($imagesRestantes != 0) {
                    $iNeedle = strpos($image, 'thumb');
                    $imglink = substr_replace($image, 'full.jpg', $iNeedle);
                    $imgURL = "http://www.naturepicoftheday.com" . $imglink;

                    if (in_array($imgURL, $rdyImages)) {
                        if ($i == 0) {
                            $i++;
                        }
                    } else {
                        $rdyImages[] = $imgURL;
                        $count++;
                        $imagesRestantes--;
                    }
                }
            }
        }
        return $rdyImages;
    }

}