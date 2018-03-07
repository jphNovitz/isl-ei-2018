<?php

namespace App\Controller\Admin\Provider;

use App\Entity\Provider ;
use App\Form\ProviderType;
use App\Service\CustomObjectLoader;
use App\Service\CustomPersister;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;use App\Service\DeleteObject;
use App\Form\DeleteType;

/**
 * Class ProviderController
 * @package App\Controller\Admin\Provider
 * @Route("/admin/providers/")
 * @Method({"GET"})
 */
class ProviderController extends Controller
{
    protected $customPersister;
    protected $customLoader;
    protected $deleter;

    public function __construct(CustomPersister $customPersister,
                                CustomObjectLoader $customObjectLoader,
                                DeleteObject $deleter)
    {
        $this->customPersister = $customPersister;
        $this->customLoader = $customObjectLoader;
        $this->deleter = $deleter;
    }

    /**
     * @Route("", name="providers_list")
     */
    public function index()
    {
        $list = $this->customLoader->LoadAll('App:Provider');
        if (!$list) {
            $this->addFlash("notice", "Aucun fournisseur trouvé, ajoutez-en un ");
            return $this->redirectToRoute('providers_add');
        }
        return $this->render($this->getParameter('adm_provider'). 'providers-list.html.twig', ['list'=>$list]);
    }

    /**
     * @Route("new", name="providers_add")
     * @Method({"GET", "POST"})
     */
    public function add(Request $request)
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()):
            if ($this->customPersister->insert($provider)){
                $this->addFlash("success", "Le fournisseur a été ajouté.");
                return $this->redirectToRoute('providers_add');
            } else {
                $this->addFlash("error", "Il y a eu un problème, fournisseur non ajouté");
            }
        endif;
        return $this->render($this->getParameter('adm_provider'). 'form/provider-add.html.twig', [
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("{slug}", name="providers_show")
     */
    public function show(Request $request, Provider $provider=null)
    {
        if (!$provider){
            $this->addFlash("error", "fournisseur inconnu");
            return $this->redirectToRoute('providers_list');
        }
        return $this->render($this->getParameter('adm_provider'). 'provider-card.html.twig', ['provider'=>$provider]);
    }



    /**
     * @Route("{slug}/update", name="providers_update")
     * @Method({"GET", "POST"})
     */
    public function update(Request $request, Provider $provider= NULL )
    {
        if (!$provider) {
            $this->addFlash("error", "fournisseur inconnu");
            return $this->redirectToRoute('providers_list');
        }
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()):
            if ($this->customPersister->update($provider)){
                $this->addFlash("success", "Le fournisseur a été modifié.");
                return $this->redirectToRoute('providers_update', ['slug'=>$provider->getSlug()]);
            } else {
                $this->addFlash("error", "Il y a eu un problème, fournisseur n'a pas pu être modifié");
            }
        endif;

        return $this->render($this->getParameter('adm_provider'). 'form/provider-update.html.twig', [
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("{slug}/delete", name="providers_delete")
     * @Method({"GET", "POST"})
     */
    public function delete(Request $request, Provider $provider = NULL )
    {
        if (!$provider){
            $this->addFlash("error", "fournisseur inconnu");
            return $this->redirectToRoute('providers_list');
        }
        $form = $this->createForm(DeleteType::class,null);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()):
            if ($form->get('oui')->isClicked()) {
                return $this->deleter->delete($provider);
            }
            if ($form->get('non')->isClicked())
                return $this->redirectToRoute('providers_list');

        endif;

        return $this->render('Admin/Provider/form/provider-delete.html.twig',
            [
                'object' => $provider,
                'form' => $form->createView()
            ]);
    }
}
