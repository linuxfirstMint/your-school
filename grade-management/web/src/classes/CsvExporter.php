<?php

namespace App;

class CsvExporter
{
    private const HEADERS = ['学生番号', 'クラス', '出席番号', '氏名', '国語', '数学', '英語', '理科', '社会', '合計'];

    public static function generate(array $exams): string
    {
        $output = fopen('php://temp', 'r+');

        // BOM なし UTF-8 ヘッダー行
        fputcsv($output, self::HEADERS);

        foreach ($exams as $exam) {
            fputcsv($output, [
                $exam['student_id'],
                $exam['grade'] . $exam['class_name'],
                $exam['student_number'],
                $exam['student_name'],
                $exam['kokugo']  ?? '',
                $exam['sugaku']  ?? '',
                $exam['eigo']    ?? '',
                $exam['rika']    ?? '',
                $exam['shakai']  ?? '',
                $exam['goukei']  ?? '',
            ]);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }
}
