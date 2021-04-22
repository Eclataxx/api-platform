<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserOrderCart extends AbstractController
{
    public function __invoke(User $data): Order
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cart = $data->getCart();
        $products = $cart->getProducts()->toArray();
        if (count($products) > 0) {
            $order = new Order();
            $order->setDate(new \DateTime());
            $order->setStatus("ORDERED");
            $order->setAssociatedUser($data);
            $order->setPrice($order->calculatePrice());

            foreach($products as $product) {
                $product->addOrder($order);
                $cart->removeProduct($product);
            }

            $entityManager->persist($order);
            $entityManager->persist($cart);
            $entityManager->flush();

            return $order;
        }
        
        throw new \LogicException("Your cart is empty");
    }
}
