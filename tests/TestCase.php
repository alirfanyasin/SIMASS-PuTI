<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Safety guard: prevent tests from ever running against local MySQL and wiping user sessions/passwords
        if (config('database.default') !== 'sqlite' || config('database.connections.sqlite.database') !== ':memory:') {
            throw new \RuntimeException('SAFETY BLOCK: Tests must run on SQLite :memory: to protect local database and active sessions! Run `php artisan config:clear`.');
        }
    }
}
