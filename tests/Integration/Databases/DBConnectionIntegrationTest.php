<?php

namespace Tests\Integration\Databases;

use App\Databases\MariaDBConnection;
use App\Env;
use PHPUnit\Framework\TestCase;

/**
 * @description db integration test that is meant to be run in the intended dockerized environment
 */
class DBConnectionIntegrationTest extends TestCase {

    protected function setUp(): void
    {
        if (
            !extension_loaded("pdo_mysql")
            || !Env::isRunningInDocker()
        ) {
            $this->markTestSkipped("This is not the proper environment to run this app");
        }
    }

    public function test_DBConnection_setsConnection() {
        $expected = MariaDBConnection::class;
        $actual = get_class(MariaDBConnection::getSingletonInstance());
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);
    }

}