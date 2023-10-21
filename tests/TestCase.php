<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use System\Application;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        Application::register()->run();
    }
}
