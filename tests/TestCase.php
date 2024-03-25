<?php

declare(strict_types=1);

namespace Symblaze\Bundle\Http\Tests;

use Faker\Factory;
use Faker\Generator as Faker;
use PHPUnit\Framework\TestCase as PHPUnit;

/**
 * Base test case class.
 */
abstract class TestCase extends PHPUnit
{
    protected Faker $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();
    }
}
