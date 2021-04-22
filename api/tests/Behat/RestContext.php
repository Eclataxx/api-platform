<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Fidry\AliceDataFixtures\Loader\PersisterLoader;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;

final class RestContext extends ApiTestCase implements Context
{
    use HeaderContextTrait;
    use FixturesContextTrait;
    use AuthContextTrait;

    /** @var Response|null */
    private $lastResponse;

    /** @var PyStringNode */
    private $lastPayload;

    /** @var PersisterLoader */
    private $fixturesLoader;

    /** @var DataList|null */
    private $dataList;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct();
        $this->fixturesLoader = $kernel->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        $this->dataList = DataList::getInstance();
    }

    /**
     * @When I request :method :path
     */
    public function iSendRequestTo(string $method, string $path): void
    {
        $options = ['headers' => $this->headers];

        if ($this->token) {
            $options['headers']['Authorization'] = $this->token;
        }

        if ($this->lastPayload) {
            $options['body'] = $this->lastPayload->getRaw();
        }

        $this->lastResponse = $this->createClient()->request($method, $path, $options);
    }

    /**
     * @When I request :method a single data from :list
     */
    public function iRequestSingleDataFromList(string $method, string $list): void
    {
        $options = ['headers' => $this->headers];

        if ($this->token) {
            $options['headers']['Authorization'] = $this->token;
        }

        if ($this->lastPayload && $method != "PATCH") {
            $options['body'] = $this->lastPayload->getRaw();
        }


        $data = $this->dataList->getData($list);
        $this->lastResponse = $this->createClient()->request($method, $data[0]["@id"], $options);
    }

    /**
     * @When I request :method :param from a single data from :list
     */
    public function iRequestProductsSingleDataFromList(string $method,string $param, string $list): void
    {
        $options = ['headers' => $this->headers];

        if ($this->token) {
            $options['headers']['Authorization'] = $this->token;
        }

        if ($this->lastPayload && $method != "PATCH") {
            $options['body'] = $this->lastPayload->getRaw();
        }


        $data = $this->dataList->getData($list);
        $this->lastResponse = $this->createClient()->request($method, $data[0]["@id"]."/".$param, $options);
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
    public function iSetPayload(PyStringNode $payload): void
    {
        $this->lastPayload = $payload;
    }

    /**
     * @param $statusCode
     * @Then The response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe($statusCode)
    {
        $statusCodeInResponse = $this->lastResponse->getStatusCode();
        if ($statusCodeInResponse != $statusCode) {
            throw new \RuntimeException("Status code error, status code received is {$statusCodeInResponse}");
        }
    }

    /**
     * @Then the :property property should equal :expectedValue
     */
    public function thePropertyEquals($property, $expectedValue)
    {
        $payload = json_decode($this->lastResponse->getContent());
        $actualValue = $payload->description;

        assertEquals(
            $expectedValue,
            $actualValue,
            "Asserting the [$property] property in current scope equals [$expectedValue]: " . json_encode($payload)
        );
    }

    /**
     * @Then I store the result in :listName
     */
    public function iStoreTheResultIn($listName)
    {
        $this->dataList->data[$listName] = $this->lastResponse->getContent();
    }
}
