<?php

namespace Tests;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testデータベースに接続できる(): void
    {
        $pdo = Database::getConnection();
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function test接続はシングルトンである(): void
    {
        $pdo1 = Database::getConnection();
        $pdo2 = Database::getConnection();
        $this->assertSame($pdo1, $pdo2);
    }
}
