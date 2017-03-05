<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Record;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\DomCrawler\Crawler;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $record = new Record();

        $form = $this->createFormBuilder($record)
            ->add('user', TextType::class)
            ->add('h', IntegerType::class)
            ->add('v', IntegerType::class)
            ->add('save', SubmitType::class, array('label' => 'Jouer'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $record = $form->getData();
            return $this->redirectToRoute('game', array(
                'user'=>$record->getUser(),
                'h'=>$record->getH(),
                'v'=>$record->getV()
            ));
        }

        return $this->render('default/index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/jeu/{user}/{h}/{v}", name="game")
     */
    public function gameAction(Request $request)
    {
        $record['user'] = $request->get('user');
        $record['h'] = $request->get('h');
        $record['v'] = $request->get('v');
        $record['countimgs'] = ($record['h']*$record['v'])/2;
        $cards = $this->_getImage($record['countimgs']);
        foreach ($cards as $card)
        {
            array_push($cards,$card);
        }

        shuffle($cards);


        $v = 0;
        $count = 0;
        for($i = 0; $i < count($cards); $i++)
        {
            if ($i < $record['h']*($v+1))
            {
                $record['plateau'][$v][$count] = $cards[$i];
            }
            else
            {
                $v++;
                $record['plateau'][$v][$count] = $cards[$i];
            }
            $count++;
            if ($count == $record['h'])
            {
                $count = 0;
            }
        }
        dump($record['plateau']);
        return $this->render('default/game.html.twig', array('record'=>$record));
    }

    private function _getImage($countImgs)
    {

        $loops = 1;

        if($countImgs % 8 == 0)
        {
            $loops = $countImgs/8;
        }
        else{
            $loops = ($countImgs/8)+1;
        }
        $count = 0;

        $rdyImages = array();
        $imagesRestantes = $countImgs;

        for ($i = $loops; $i != 0; $i-- )
        {
            $url = 'http://www.naturepicoftheday.com/random';
            $html = file_get_contents($url);
            $crawler = new Crawler($html);
            $images = $crawler
                ->filterXpath('//span[contains(@class, "PLAIN_LNK")]')
                ->filterXpath('//img')
                ->extract(array('src'));

            foreach ($images as $image)
            {
                if ($imagesRestantes != 0)
                {
                    $iNeedle = strpos($image, 'thumb');
                    $imglink = substr_replace($image, 'full.jpg', $iNeedle);
                    $imgURL = "http://www.naturepicoftheday.com".$imglink;

                    if (in_array($imgURL, $rdyImages))
                    {
                        if ($i == 0){$i++;}
                    }
                    else
                    {
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
