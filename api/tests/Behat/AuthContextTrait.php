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
        $response = self::createClient()->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/ld+json'],
            'json' => [
                'email' => $this->getMockedUserEmail($role),
                'password' => 'secret',
            ],
        ]);

        $json = $response->toArray();

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        $this->token = "Bearer {$json['token']}";
    }

    protected function getMockedUserEmail(string $role): string
    {
        return strtolower($role).'@gmail.com';
    }
}
