<?php

namespace App\Controller\Api\Ingredient;

use App\Service\CustomObjectLoader;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;


class CategoryController extends FOSRestController{

    protected  $customLoader;
    public function __construct(CustomObjectLoader $customObjectLoader)
    {
        $this->customLoader = $customObjectLoader;
    }



    /**
     * @Get("categories", name="get_categories")
     */
    public function getcategories()
    {
        return  $this->customLoader->CreateJsonResponse('App:Category');
    }

}