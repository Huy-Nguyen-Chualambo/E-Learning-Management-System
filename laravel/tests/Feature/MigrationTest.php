<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_roles_table_is_created()
    {
        $this->assertFalse(\Schema::hasTable('roles'));

        $this->artisan('migrate');

        $this->assertTrue(\Schema::hasTable('roles'));
    }
}