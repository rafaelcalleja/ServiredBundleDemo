<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Acme\DemoBundle\Form\ContactType;
use Acme\DemoBundle\Form\ProductType;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DemoController extends Controller
{

    /**
     * @Route("/retrypayment", name="_demo_retrypayment")
     * @Template()
     */
    public function retryAction(){

       $em = $this->get('doctrine')->getManagerForClass('Acme\DemoBundle\Entity\Sale');
       $sale = $em->getRepository('Acme\DemoBundle\Entity\Sale')->findOneBy(array('id' => 1));
       $sale->setSaleNumber(time());

       $em->persist($sale);
       $em->flush();

       $this->get('rc_servired.session.manager')->setOrder( $sale->getSaleNumber() );

       return new RedirectResponse( $this->generateUrl('rc_servired_retry') );
    }

    /**
     * @Route("/product", name="_demo_product")
     * @Template()
     */
    public function productAction(){

        $sale = new Sale();
        $form = $this->createForm(new ProductType(), $sale);

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {

                $em = $this->get('doctrine')->getManagerForClass('Acme\DemoBundle\Entity\Sale');
                $em->persist($sale);
                $em->flush();

                return new RedirectResponse($this->generateUrl('rc_servired_payment', array('amount' => $sale->getPrice(), 'id' => $sale->getTransaction()->getDsOrder() )));
            }
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/done")
     */
    public function doneAction()
    {
        return new Response('done');
    }

    /**
     * @Route("/failed")
     */
    public function failedAction()
    {
        return new Response('failed');
    }

    /**
     * @Route("/success")
     */
    public function successAction()
    {
        return new Response('success');
    }



    /**
     * @Route("/", name="_demo")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/hello/{name}", name="_demo_hello")
     * @Template()
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

    /**
     * @Route("/contact", name="_demo_contact")
     * @Template()
     */
    public function contactAction()
    {
        $form = $this->get('form.factory')->create(new ContactType());

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $form->submit($request);
            if ($form->isValid()) {
                $mailer = $this->get('mailer');
                // .. setup a message and send it
                // http://symfony.com/doc/current/cookbook/email.html

                $this->get('session')->getFlashBag()->set('notice', 'Message sent!');

                return new RedirectResponse($this->generateUrl('_demo'));
            }
        }

        return array('form' => $form->createView());
    }
}
