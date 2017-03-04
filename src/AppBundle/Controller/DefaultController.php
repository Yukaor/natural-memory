<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Record;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;


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
        $record['nbImages'] = ($record['h']*$record['v'])/2;
        $this->getImagesAction($record['nbImages']);
        return $this->render('default/game.html.twig', array('record'=>$record));
    }

    public function getImagesAction($nbImage)
    {
        $fs = new Filesystem();

        for ($i = 1; $i <= $nbImage; $i++)
        {
            $fs->copy('https://source.unsplash.com/category/nature/200x200.jpeg', '/web/'.$i.'.jpeg');
            exit;
        }

        return;
    }

}
