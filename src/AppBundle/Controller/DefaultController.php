<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Record;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use AppBundle\Service;


class DefaultController extends Controller
{
    private $imageService;

    /**
     * @return mixed
     */
    public function getImageService()
    {
        return $this->imageService;
    }

    /**
     * @param mixed $imageService
     * @return DefaultController
     */
    public function setImageService($imageService)
    {
        $this->imageService = $imageService;
        return $this;
    }


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

        $this->setImageService(new Service\ImageService());
        $record['user'] = $request->get('user');
        $record['h'] = $request->get('h');
        $record['v'] = $request->get('v');
        $record['countimgs'] = ($record['h']*$record['v'])/2;
        $cards = $this->getImageService()->getImageNature($record['countimgs']);
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

        return $this->render('default/game.html.twig', array('record'=>$record));
    }
}
