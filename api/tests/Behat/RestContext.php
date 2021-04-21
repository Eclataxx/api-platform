<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpKernel\KernelInterface;

final class RestContext extends ApiTestCase implements Context
{
    use RefreshDatabaseTrait;
    use HeaderContextTrait;
    use FixturesContextTrait;

    /** @var Response|null */
    private $lastResponse;

    /** @var PyStringNode */
    private $lastPayload;

    /** @var PersisterLoader */
    private $fixturesLoader;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->fixturesLoader = $kernel->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
    }

    /**
     * @When I request :method :path
     */
    public function iSendARequestTo(string $method, string $path): void
    {
        $options = ['headers' => $this->headers];
        if ($this->lastPayload) {
            $options['body'] = $this->lastPayload->getRaw();
        }

        $this->lastResponse = $this->createClient()->request($method, $path, $options);
    }

    /**
     * PAS UTILE SI PAYLOAD
     * @When I request :method :path with data
     */
    public function iSendARequestWithData(string $method, string $path, PyStringNode $parameters): void
    {
        $this->lastResponse = $this->createClient()->request($method, $path, [
            'headers' => [
                'content-type' => 'application/ld+json'
            ],
            'body' => $parameters->getRaw()
        ]);
    }

    /**
     * @When I set payload
     */
    public function iSetPayload( PyStringNode $payload): void
    {
        $this->lastPayload = $payload;
    }

    /**
     * @param $statusCode
     * @Then The response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        if ($this->lastResponse->getStatusCode() != $statusCode) {
            throw new \RuntimeException('Status code error');
        }
    }
}
