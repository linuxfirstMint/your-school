<?php

namespace Tests;

use App\CsvExporter;
use PHPUnit\Framework\TestCase;

class CsvExporterTest extends TestCase
{
    public function testヘッダー行が含まれる(): void
    {
        $csv = CsvExporter::generate([]);
        $lines = explode("\n", trim($csv));

        $this->assertSame(
            '学生番号,クラス,出席番号,氏名,国語,数学,英語,理科,社会,合計',
            $lines[0]
        );
    }

    public function testデータ行が正しく出力される(): void
    {
        $exams = [
            [
                'student_id'     => 1,
                'grade'          => '1年',
                'class_name'     => 'A組',
                'student_number' => 1,
                'student_name'   => '山田 太郎',
                'kokugo'         => 85,
                'sugaku'         => 90,
                'eigo'           => 78,
                'rika'           => 88,
                'shakai'         => 92,
                'goukei'         => 433,
            ],
        ];

        $csv = CsvExporter::generate($exams);
        $lines = explode("\n", trim($csv));

        $this->assertSame('1,1年A組,1,"山田 太郎",85,90,78,88,92,433', $lines[1]);
    }

    public function test未入力の点数は空文字で出力される(): void
    {
        $exams = [
            [
                'student_id'     => 2,
                'grade'          => '1年',
                'class_name'     => 'B組',
                'student_number' => 1,
                'student_name'   => '鈴木 花子',
                'kokugo'         => null,
                'sugaku'         => null,
                'eigo'           => null,
                'rika'           => null,
                'shakai'         => null,
                'goukei'         => null,
            ],
        ];

        $csv = CsvExporter::generate($exams);
        $lines = explode("\n", trim($csv));

        $this->assertSame('2,1年B組,1,"鈴木 花子",,,,,,' , $lines[1]);
    }

    public function test複数行出力できる(): void
    {
        $exams = [
            [
                'student_id' => 1, 'grade' => '1年', 'class_name' => 'A組',
                'student_number' => 1, 'student_name' => '山田 太郎',
                'kokugo' => 85, 'sugaku' => 90, 'eigo' => 78,
                'rika' => 88, 'shakai' => 92, 'goukei' => 433,
            ],
            [
                'student_id' => 2, 'grade' => '1年', 'class_name' => 'A組',
                'student_number' => 2, 'student_name' => '鈴木 花子',
                'kokugo' => 72, 'sugaku' => 65, 'eigo' => 80,
                'rika' => 70, 'shakai' => 75, 'goukei' => 362,
            ],
        ];

        $csv = CsvExporter::generate($exams);
        $lines = explode("\n", trim($csv));

        $this->assertCount(3, $lines); // ヘッダー + 2行
    }

    public function testカンマを含む氏名はダブルクォートで囲まれる(): void
    {
        $exams = [
            [
                'student_id' => 1, 'grade' => '1年', 'class_name' => 'A組',
                'student_number' => 1, 'student_name' => '山田,太郎',
                'kokugo' => 85, 'sugaku' => 90, 'eigo' => 78,
                'rika' => 88, 'shakai' => 92, 'goukei' => 433,
            ],
        ];

        $csv = CsvExporter::generate($exams);
        $lines = explode("\n", trim($csv));

        $this->assertStringContainsString('"山田,太郎"', $lines[1]);
    }
}
