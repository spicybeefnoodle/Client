<?php

declare(strict_types=1);

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Tests;

use DigitalOceanV2\Client;
use DigitalOceanV2\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;

/**
 * @author Graham Campbell <graham@alt-three.com>
 */
final class IntegrationTest extends TestCase
{
    public function testWithoutAuth(): void
    {
        $client = new Client();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to authenticate you');

        $client->account()->getUserInformation();
    }
}
