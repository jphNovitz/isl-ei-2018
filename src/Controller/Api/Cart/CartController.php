<?php

namespace App\Controller\Api\Cart;


use App\Entity\Cart;
use App\Entity\Item;
use App\Entity\Product;
use App\Model\CustomPersisterInterface;
use App\Service\CustomObjectLoader;
use App\Service\CustomPersister;
use App\Service\SocketNotifier;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Patch;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use Hateoas\HateoasBuilder;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class CartController
 * @package App\Controller\Api\Cart
 */
class CartController extends FOSRestController
{
    protected $serializer;
    protected $customPersister;
    protected $customLoader;
    protected $em;
    protected $notifier;
    protected $logger;
    public function __construct(CustomObjectLoader $customObjectLoader,
                                CustomPersisterInterface $customPersister,
                                ContainerInterface $container,
                                EntityManagerInterface $entityManager, SocketNotifier $notifier)
    {
        $this->customPersister = $customPersister;
        $this->customLoader= $customObjectLoader;
        $this->serializer = $container->get('jms_serializer');
        $this->notifier = $notifier;
        $this->em = $entityManager;
    }

    /**
     * @Get("s/orders", name="orders_list")
     */
    public function list(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_MEMBER');
        $result =  $this->get('doctrine.orm.default_entity_manager')
                ->getRepository('App:Cart')
                ->findAllOrders();
        if (!$result) {
            $status = 500;
            $result = ['message'=>'inconnu'];
        } else{
            $status = 200;
        }
        $response = $this->prepare($result, $status);
        return ($response);
    }


    /**
     * @Get("pending")
     */
    public function pending(Request $request) {
       
        $result =  $this->get('doctrine.orm.default_entity_manager')
                ->getRepository('App:Cart')
                ->findAllPending();

        if (!$result) {
            $result = ['message'=>'empty'];
        } 
         $status = 200;
        $response = $this->prepare($result, $status);
        return ($response);
    }

    /**
     * @Post("s/cart", name="cart_new")
     */
    public function post(Request $request, LoggerInterface $logger)
    {
        $cart = new Cart();
        $box = json_decode($request->getContent(), true);
        $username = $box['user'];
        $user = $this->em = $this->get('doctrine.orm.entity_manager')
            ->getRepository('App:User')->findOneBy(['username'=>$username]);

        $cart->setClient($user); 
        foreach ($box['items']  as $item)
            {
		
                $line = new Item();
                $product = $this->customLoader->LoadOne('App:Product', $item['slug']);
                $line->setProduct($product);
                $line->setPrice($item['price']);
                $line->setBread($item['bread']);
		$line->setSauce($item['sauce']);
		$line->setHalal($item['halal']);
		foreach ($item['vegetables'] as $vege){
			$newVege = $this->em = $this->get('doctrine.orm.entity_manager')
		            ->getRepository('App:Ingredient')->findOneBy(['name'=>$vege]);

			$line->addVegetable($newVege);
		}
            
               $cart->addItem($line);
            }


        $hateoas = HateoasBuilder::create()->build();
        if ($this->customPersister->insert($cart)){
            $result =  $hateoas->serialize($cart, 'json');
            $status = 200;;
        } else {
            $result = ['message'=>'erreur serveur'];
            $status = 500;
        }

        try {
            $this->notifier->notify('new_order', ['order'=>$result]);
		
        } catch (\Exception $e){
            $logger->warning('connection impossible',['meesage'=>$e]);
        }
        $response = $this->prepare($result, $status);
        return ($response);
    }

    /**
     * @Delete("order/{id}", name="orders_delete")
     */
    public function delete(Cart $order, Request $request, LoggerInterface $logger) {
        if ($order && $this->isGranted('ROLE_MEMBER')) {

            $removed = $order->getId();
            try {
                $this->em->remove($order);
                $this->em->flush();
                $status = 200;
                $result = ['message' => 'commande ' . $removed . ' supprimee'];
            } catch (\Doctrine\DBAL\DBALException $e) {
                $status = 500;
                $result = ['message' => 'erreur de suppression'];
            }
            try {
                $this->notifier->notify('remove_order', ['id' => $removed]);
            } catch (\Exception $e) {
                $logger->warning('connection impossible', ['meesage' => $e]);
            }
  /**/      }else {
            $status = 500;
            $result = ['error' => 'erreur de suppression'];
        }/**/
        $response = $this->prepare($result, $status);
        return ($response);
    }

    /**
     * @Patch("order/{id}/done", name="orders_done")
     */
    public function done(Cart $order, Request $request, LoggerInterface $logger) {

        if ($order && $this->isGranted('ROLE_MEMBER')) {
            try {
                $order->setDone(true);
                $this->customPersister->update($order);
                $status = 200;
                $result = ['message' => $order->getId()];
            } catch (\Doctrine\DBAL\DBALException $e) {
                $status = 500;
                $result = ['error' => 'erreur de suppression'];
            }

            try {
                $this->notifier->notify('update_order', $result);
            } catch (\Exception $e) {
                $logger->warning('connection impossible', ['meesage' => $e]);
            }
        } else {
            $status = 500;
            $result = ['error' => 'erreur de mise a jour'];
        }

        $response = $this->prepare($result, $status);
        return ($response);
    }

    protected function prepare($result, $status){
        $hateoas = HateoasBuilder::create()->build();
        $json = $hateoas->serialize($result, 'json');
        $response = new Response($json, $status, array('application/json'));
        $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET');
        return $response;
    }
}
