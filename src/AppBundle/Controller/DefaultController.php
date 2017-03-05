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
            if (($record->getH()*$record->getV()) % 2 != 0)
            {
                $message = "Veuillez choisir un nombre dont (horizontal * vertical)/2 est pair.";
                return $this->render('default/index.html.twig', array('form' => $form->createView(), 'message' => $message));

            }
            $record = $form->getData();
            return $this->redirectToRoute('game', array(
                'user'=>$record->getUser(),
                'h'=>$record->getH(),
                'v'=>$record->getV()
            ));
        }
        $message = "";
        return $this->render('default/index.html.twig', array('form' => $form->createView(), 'message' => $message));
    }

    /**
     * @Route("/jeu/{user}/{h}/{v}", name="game")
     */
    public function gameAction(Request $request)
    {
        $recordToDB = new Record();

        $form = $this->createFormBuilder($recordToDB)->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $form = $request->get('form');
            $recordToDB->setH($form['h']);
            $recordToDB->setV($form['v']);
            $recordToDB->setUser($form['user']);
            $recordToDB->setTime($form['time']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($recordToDB);
            $em->flush();
            return $this->redirectToRoute('scoreboard');
        }

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

    /**
     * @Route("/scoreboard", name="scoreboard")
     */
    public function scoreboardAction()
    {
        $repository  = $this->getDoctrine()->getRepository('AppBundle:Record');
        $query = $repository->createQueryBuilder('r')
            ->orderBy('r.time', 'ASC')
            ->getQuery();
        $records = $query->getResult();


        return $this->render('default/scoreboard.html.twig', array('records'=>$records));
    }
}
