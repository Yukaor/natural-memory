<?php
/**
 * Created by PhpStorm.
 * User: utilisateur
 * Date: 05/03/17
 * Time: 13:49
 */

namespace AppBundle\Service;

use Symfony\Component\DomCrawler\Crawler;

class ImageService
{
    public function __construct()
    {
    }

    private function _LoopsDetection($countImages)
    {
        if ($countImages / 8 <= 1) {
            $loops = 1;
        } elseif ($countImages % 8 == 0) {
            $loops = $countImages / 8;
        } else {
            $loops = floor($countImages / 8) + 1;
        }

        return $loops;
    }

    public function getImageNature($countImgs)
    {

        $rdyImages = array();
        $imagesRestantes = $countImgs;

        $Index = $this->_LoopsDetection($countImgs);

        for ($i = $Index; $i != 0; $i--) {
            $url = 'http://www.naturepicoftheday.com/random';
            $html = file_get_contents($url);
            $crawler = new Crawler($html);
            $images = $crawler
                ->filterXpath('//span[contains(@class, "PLAIN_LNK")]')
                ->filterXpath('//img')
                ->extract(array('src'));

            foreach ($images as $image) {
                if ($imagesRestantes != 0) {

//                    $iNeedle = strpos($image, 'thumb');
//                    $imglink = substr_replace($image, 'full.jpg', $iNeedle);
                    $imgURL = "http://www.naturepicoftheday.com" . $image;

                    if (in_array($imgURL, $rdyImages)) {
                        if ($i == 0) {
                            $i++;
                        }
                    } else {
                        $rdyImages[] = $imgURL;
                        $imagesRestantes--;
                    }
                }
            }
        }
        return $rdyImages;
    }

}