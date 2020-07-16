<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\CampaignType;
use Doctrine\ORM\Query\AST\Functions\SumFunction;
use PhpParser\Node\Expr\Cast\Int_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Flex\Path;

/**
 * @Route("/campaign")
 */
class CampaignController extends AbstractController
{
    /**
     * @Route("/", name="campaign_index", methods={"GET"})
     */
    public function index(): Response
    {
        $campaigns = $this->getDoctrine()
            ->getRepository(Campaign::class)
            ->findAll();

        return $this->render('campaign/index.html.twig', [
            'campaigns' => $campaigns,
        ]);
    }
  
    /**
     * @Route("/new", name="campaign_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
       
        $campaign = new Campaign();
        
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);
        $cagname= $request->request->get('cag_name');
        // dd($cagname);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $campaign->setId();
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_show',['id'=> $campaign->getId()]);
        }

        return $this->render('campaign/new.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
            'cagname'=>$cagname
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_show", methods={"GET"})
     */
    public function show(Campaign $campaign): Response

    {   
        // count de participants
       $participants = $this->getDoctrine()
            ->getRepository(Participant::class)
            ->findBy(['campaign' => $campaign]);
            $participantCount = count($participants);
            
        
        // somme des participations
        $payments = $this->getDoctrine()
            ->getrepository(Payment::class)
            ->findBy(['participant'=>$participants]);
            // dd($totalAmount);
           
           $sum=0;
           foreach($payments as $payment){
               $sum += $payment->getAmount();
               
           }
           
        // barre de progression   
        $goal= $campaign->getGoal();
        $percent=intval( ($sum/$goal)*100);

        // $email =$payment;
        //     dd($email);

               
            


            
        
        return $this->render('campaign/show.html.twig', [
            'campaign' => $campaign,
            'participantCount'=>$participantCount,
            'payments'=> $payments,
            'sum'=>$sum,
            'percent'=> $percent,

            
        ]);
        
    }

    /**
     * @Route("/{id}/edit", name="campaign_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campaign $campaign): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('campaign_show',['id'=> $campaign->getId()]);
        }

        return $this->render('campaign/edit.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Campaign $campaign): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaign->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($campaign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('campaign_index');
    }

    





}
