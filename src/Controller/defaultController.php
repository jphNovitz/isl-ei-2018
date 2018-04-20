<?php

namespace App\Controller;

use App\Service\FeaturedProducts;
use mageekguy\atoum\asserters\variable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class defaultController extends Controller {


    /**
     * @Route("/", name="default")
     */
    public function index(FeaturedProducts $featured){
       $featured_products = $featured->getFeatured();
       $this->get('app.products')->featured =  $featured_products;
       return $this->render('index.html.twig');

    }

}
