<?php

namespace App\Controller;
use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\PaymentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{
    /** 
     * @Route("/{id}", name="payment_form")
     */

    public function newPayment(Campaign $campaign, Request $request): Response
    {
        $amount = intval($request->request->get('mount'));

        $payment = new Payment();

        $form = $this->createForm(PaymentType::class, $payment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $payment->getParticipant()->setCampaign($campaign);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($payment->getParticipant());
            $entityManager->persist($payment);
            $entityManager->flush();
           
             // Set your secret key. Remember to switch to your live secret key in production!
            // See your keys here: https://dashboard.stripe.com/account/apikeys
            \Stripe\Stripe::setApiKey('sk_test_51H2XZoC9AgcSjzXzNZ87NB3SquAdSVnYZZRgxVDSJqAf2zzrdidoqSPFShFCGIdaQpG0IKJWAIt1aWwE8nwrTME800YZ9Xmhje');
             
            try{
            // Token is created using Stripe Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $token = $request->request->get('stripeToken');
            
            $charge = \Stripe\PaymentIntent::create([
            'amount' =>intval( $payment->getAmount()),
            'currency' => 'eur',
            'description' => 'Example charge',
            'source' => $token,
            
            ]);
            
            } catch (\Exception $e) {
                dd ( 'catch');
            }

          

            return $this->redirectToRoute('campaign_show', [
                'id' => $campaign->getId(),
            ]);

            
             
            
        }
        return $this->render('campaign/payment2.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
            'amount' => $amount
        ]);
        
        
    }
}





    // public function showPaymentForm(Campaign $campaign, Request $request):Response
    // {

    //     $amount = $request->request->get('mount');

       

    //     return $this->render('campaign/payment.html.twig', [
    //         'controller_name' => 'PaymentController',
    //         'campaign'=>$campaign,
    //         'amount'=>$amount


    //     ]);
    // }
    
    
    
     /**
     *@Route("/savepayment/{id}", name="payment_save", methods={"GET","POST"})
    
     */
    //  public function savePaymentInfos(Campaign $campaign, Request $request): Response
    //  {  
         
     
    //     $participant = new Participant();
    //     $payment=new Payment();
    //  //    dd($participant,$payment);
    //     $em=$this->getDoctrine()->getManager();
    //     $name=$request->request->get('name');
    //     $email=$request->request->get('email');
    //     $amount=$request->request->get('amount');
        
    //     // dd($amount);
     
    //     $participant->setName($name);
    //     $participant->setEmail($email);
    //     $participant->setCampaign($campaign);

    //     $em->persist($participant);
    //     $em->flush();

        
    //     $payment->setAmount($amount);
    //     $payment->setParticipant($participant);
    //     $payment->setCreatedAt(new \DateTime());
    //     $payment->setUpdatedAt(new \DateTime());
    //     $em->persist($payment);
    //     $em->flush();


        // return $this->redirectToRoute('campaign_show',['id' => $request->request->get('campaign_id')]);

    //  }

    



    

