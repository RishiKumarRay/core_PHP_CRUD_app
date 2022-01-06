<?php

namespace Tests\Integration;

use App\DBConnection;
use App\Env;
use PDO;
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
        $expected = PDO::class;
        $actual = get_class(DBConnection::getInstance()->getConnection());
        $this->assertNotNull($actual);
        $this->assertEquals($expected, $actual);
    }

}