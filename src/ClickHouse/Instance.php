<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse;

use ClickHouseDB\Client;

class Instance
{
    private $client;

    /**
     * Instance constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Builder
     */
    public function createQueryBuilder(): Builder
    {
        return new Builder($this->client);
    }
}