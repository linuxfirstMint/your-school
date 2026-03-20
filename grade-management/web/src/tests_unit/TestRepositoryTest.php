<?php

namespace Tests;

use App\Database;
use App\TestRepository;
use PHPUnit\Framework\TestCase;

class TestRepositoryTest extends TestCase
{
    private TestRepository $repo;

    protected function setUp(): void
    {
        $this->repo = new TestRepository();
        Database::getConnection()->exec('DELETE FROM tests');
    }

    protected function tearDown(): void
    {
        Database::getConnection()->exec('DELETE FROM tests');
    }

    public function testテストを登録できる(): void
    {
        $id = $this->repo->create(2024, '前期中間テスト');
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }

    public function test全件取得できる(): void
    {
        $this->repo->create(2024, '前期中間テスト');
        $this->repo->create(2024, '前期期末テスト');

        $tests = $this->repo->findAll();
        $this->assertCount(2, $tests);
    }

    public function testIDで1件取得できる(): void
    {
        $id = $this->repo->create(2024, '後期中間テスト');

        $test = $this->repo->findById($id);
        $this->assertSame(2024, (int) $test['year']);
        $this->assertSame('後期中間テスト', $test['name']);
    }

    public function testテストを更新できる(): void
    {
        $id = $this->repo->create(2024, '前期中間テスト');
        $this->repo->update($id, 2025, '後期期末テスト');

        $test = $this->repo->findById($id);
        $this->assertSame(2025, (int) $test['year']);
        $this->assertSame('後期期末テスト', $test['name']);
    }

    public function testテストを削除できる(): void
    {
        $id = $this->repo->create(2024, '前期中間テスト');
        $this->repo->delete($id);

        $test = $this->repo->findById($id);
        $this->assertFalse($test);
    }
}
