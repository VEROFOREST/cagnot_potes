<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Campaign;
use App\Entity\Payment;
use App\Entity\Participant;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $participants = $this->getDoctrine()
            ->getRepository(Participant::class)
            ->findAll();
       
         $campaigns = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findAll();
         $allCampaignsInfos = $this->getDoctrine()
            ->getRepository(Payment::class)
            ->findBy(['participant'=>$participants]);
        
        
      
        
         

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'campaigns'=>$campaigns,
            'allCampaignsInfos'=>$allCampaignsInfos,
        ]);
    }
  
    
}
