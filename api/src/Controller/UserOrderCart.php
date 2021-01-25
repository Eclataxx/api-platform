<?php


namespace App\Controller;

use App\Entity\User;
use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserOrderCart extends AbstractController
{
    public function __invoke(User $data): User
    {
        $entityManager = $this->getDoctrine()->getManager();

        $cart = $data->getCart();
        $products = $cart->getProducts()->toArray();
        if (count($products) > 0) {
            $order = new Order(new \DateTime(), "ORDERED");
            $order->setAssociatedUser($data);
            $order->setPrice($order->calculatePrice());

            foreach($products as $product) {
                $product->addOrder($order);
                $cart->removeProduct($product);
            }

            $entityManager->persist($order);
            $entityManager->persist($cart);
            $entityManager->flush();
        }

        return $data;
    }
}
