<?php

namespace App\Tests\Behat;

use App\Entity\User;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\EntityManagerInterface;

trait AuthContextTrait
{
    /** @var string */
    private $token;

    /**
     * @Given a user with role :role
     */
    public function IamAuthenticatedAs(string $role): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUsername('user');
        $user->setPassword(
            self::$container->get('security.password_encoder')->encodePassword($user, '$3CR3T')
        );
        $user->setRoles([$this->getRole($role)]);

        $manager = self::$container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        $response = $this->createClient()->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'email' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        $this->token = "Bearer {$json['token']}";
    }

    private function getRole(string $role): string
    {
        return strtoupper("role_{$role}");
    }
}
