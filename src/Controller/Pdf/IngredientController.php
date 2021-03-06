<?php

Namespace App\Controller\Pdf;

use App\Entity\Ingredient;
use App\Service\CustomObjectLoader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;


/**
 * Class IngredientController
 * @package App\Controller\Pdf\Ingredient
 * @Route("/admin/ingredients/pdf/", methods={"GET","HEAD"})
 */

class IngredientController extends Controller
{

    protected $generator ;
    protected $twig ;
    protected $loader ;

    public function __construct(Pdf $generator, Environment $twig, CustomObjectLoader $loader)
    {
        $this->generator = $generator;
        $this->twig = $twig;
        $this->loader = $loader;
    }

    /**
     * @Route("{slug}", name="ingredient_pdf")
     */
    public function create(String $slug)
    {
        $ingredient = $this->loader->LoadOne('App:Ingredient', $slug);
        $this->generator->setTimeout('1000');
        try {
            $this->generator->generateFromHtml(
                $this->twig->render(
                    'Pdf/ingredient-pdf.html.twig',
                    array(
                        'ingredient' => $ingredient
                    )
                ),
                'pdf/documents/ingredients/' . $ingredient->getSlug() . '.pdf',
                [],
                true
            );
            return $ingredient->getSlug();

        } catch (\Exception $e){dump($e->getMessage()); die();
            return false;
//            return 'erreur: '.$e->getMessage();
        }
    }


    /**
     * @Route("{slug}/view", name="ingredient_view_pdf")
     */
    public function view(String $slug)
    {
        return $this->render('Pdf/pdf-confirmation.html.twig', [
                "file" => 'pdf/documents/ingredients/' . $slug . '.pdf'
            ]);
    }
}