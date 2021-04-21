<?php

namespace App\Tests\Behat;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertTrue;

trait HeaderContextTrait
{
    /** @var array */
    private $headers = ['content-type' => 'application/ld+json'];

    /**
     * @param $headerName
     * @param $value
     * @Given I set the :headerName header to be :value
     */
    public function iSetHeaderToBe($headerName, $value)
    {
        $this->headers[$headerName] = $value;
    }

    /**
     * @param $headerName
     * @param $value
     * @Given The :headerName header response should be :value
     */
    public function theHeaderResponseShouldBe($headerName, $value)
    {
        assertEquals($value, $this->lastResponse->getHeaders()[$headerName][0]);
    }

    /**
     * @param $headerName
     * @Given The :headerName header response should exist
     */
    public function theHeaderResponseShouldExist($headerName)
    {
        assertTrue(isset($this->lastResponse->getHeaders()[$headerName][0]));
    }
}
