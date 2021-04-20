<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class RestContext implements Context
{
    use RefreshDatabaseTrait;

    /** @var Response|null */
    private $lastResponse;

    /** @var PyStringNode */
    private $payload;

    /** @var HttpClientInterface */
    private $client;

    public function __construct(KernelInterface $kernel)
    {
        $this->client = $kernel->getContainer()->get('test.api_platform.client');
    }

    /**
     * @param PyStringNode $payload
     * @When I have The Payload
     */
    public function iHavePayload(PyStringNode $payload)
    {
        $this->payload = $payload;
    }

    /**
     * @When /^I request "(GET|PUT|POST|PATCH|DELETE) ([^"]*)"$/
     */
    public function iSendARequestTo($method, $path)
    {
        $options = ['headers' => ['content-type' => 'application/ld+json']];

        if ($this->payload) {
            $options['body'] = $this->payload->getRaw();
            $this->payload = null;
        }

        $this->lastResponse = $this->client->request($method, $path, $options);
    }

    /**
     * @Then /^the response status code should be (?P<code>\d+)$/
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        if ($this->lastResponse->getStatusCode() != $statusCode) {
            throw new \RuntimeException('No response received');
        }
    }
}
