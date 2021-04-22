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
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;

final class RestContext extends ApiTestCase implements Context
{
    use HeaderContextTrait;
    use FixturesContextTrait;
    use AuthContextTrait;
    // use RefreshDatabaseTrait;
     use BaseDatabaseTrait;
     use HookContextTrait;

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

    public function getResourceURI(string $string): ?string
    {
        $matches = [];
        preg_match('/\/(?<type>.*)\/{(?<name>.*)\.(?<field>.*)}(\/(?<end>.*))?/', $string, $matches);
        if (
            isset($matches['type']) &&
            isset($matches['name']) &&
            isset($matches['field'])
        ) {
            $userId = $this->dataList->data[$matches['name']][$matches['field']];
            if (isset($matches['end'])) {
                return "/{$matches['type']}/{$userId}/{$matches['end']}";
            }
            return "/{$matches['type']}/{$userId}";
        }
        return "";
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
            $options['body'] = $this->lastPayload;
        }

        $resourceURI = $this->getResourceURI($path);
        if ($resourceURI) {
            $this->lastResponse = $this->createClient()->request($method, $resourceURI, $options);
            return;
        }
        $this->lastResponse = $this->createClient()->request($method, $path, $options);
    }

    /**
     * @When I set payload
     */
    public function iSetPayload(PyStringNode $payload): void
    {
        $values = json_decode($payload->getRaw(), true);
        if (isset($values["submittedBy"])) {
            $values["submittedBy"] = $this->getResourceURI($values["submittedBy"]);
        }
        if(isset($values["cart"])) {
            $values["cart"] = $this->getResourceURI($values["cart"]);
        }
        if (isset($values["products"])) {
            $values["products"][0] = $this->getResourceURI($values["products"][0]);
        }
        if (isset($values["associatedUser"])) {
            $values["associatedUser"] = $this->getResourceURI($values["associatedUser"]);
        }
        $this->lastPayload = json_encode($values);
        var_dump($this->lastPayload);
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
     * @Then all the :property properties should equal :expectedValue
     */
    public function thePropertyInListEquals($property, $expectedValue)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);

        foreach ($payload["hydra:member"] as $value) {
            $actualValue = $value[$property];
            assertEquals(
                $expectedValue,
                $actualValue,
            );
        }
    }

    /**
     * @Then the :property property should equal :expectedValue
     */
    public function thePropertyEquals($property, $expectedValue)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);
        $actualValue = $payload[$property];
        assertEquals(
            $expectedValue,
            $actualValue,
        );
    }

    /**
     * @Then the :property property should exist
     */
    public function thePropertyExists(string $property)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);
        assertTrue($this->arrayHas($payload, $property));
    }

    /**
     * @Then the :property property should not exist
     */
    public function thePropertyDoesNotExists(string $property)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);
        assertFalse($this->arrayHas($payload, $property));
    }

    /**
     * @Then all the :property properties should exist
     */
    public function thePropertyInListExists(string $property)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);
        foreach ($payload["hydra:member"] as $value) {
            assertTrue($this->arrayHas($value, $property));
        }
    }
    /**
     * @Then all the :property properties should not exist
     */
    public function thePropertyInListDoesNotExists(string $property)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);
        foreach ($payload["hydra:member"] as $value) {
            assertFalse($this->arrayHas($value, $property));
        }
    }

    protected function arrayHas($array, $key)
    {
        return array_key_exists($key, $array);
    }


     /**
     * @Then the :property property should be an empty array
     */
    public function thePropertyIsAnEmptyArray(string $property)
    {
        $payload = json_decode($this->lastResponse->getContent(), true);
        assertTrue(
            is_array($payload[$property]) and $payload[$property] === array()
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
