<?php

namespace App\controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use App\utils\Helper;

class ExcelController
{
    public function index()
    {
        try {
            $file = UPLOAD_DIR . uniqid() . '.xlsx';
            $spreadsheet = new Spreadsheet();

            // Set document data
            $data = $_SESSION['download_data'] ?? [];
            if (!$data) {
                Helper::redirectTo('/', [
                    'status' => 'error',
                    'message' => 'Không có dữ liệu để xuất file excel!'
                ]);
                return;
            }

            unset($_SESSION['download_data']);
            $this->fillSheet(
                $spreadsheet,
                $data['title'],
                $data['header'],
                $data['data']
            );

            // Create a new worksheet
            $writer = new Xlsx($spreadsheet);
            $writer->save($file);

            // Download the file
            $this->download($file, 'download.xlsx');

            // Remove from upload folder // TODO: it not work
            unlink($file);

            // Redirect back
            Helper::redirectTo(Helper::getOnceFromSession('previous_page') ?? '/', [
                'status' => 'success',
                'message' => 'Xuất file excel thành công!'
            ]);
        } catch (Exception $e) {
            Helper::redirectTo(Helper::getOnceFromSession('previous_page') ?? '/', [
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xuất file excel!'
            ]);
        }
    }

    public function download(string $file, string $filename = null)
    {
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . ($filename ?? basename($file)) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
    }

    private function setTitle(Spreadsheet $spreadsheet, string $maxCol, string $title)
    {
        $spreadsheet->getActiveSheet()->mergeCells("A1:{$maxCol}1", Worksheet::MERGE_CELL_CONTENT_MERGE);
        $spreadsheet->getActiveSheet()->setCellValue('A1', $title);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(14);
    }

    private function setHeader(Spreadsheet $spreadsheet, string $maxCol, array $header)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($header, null, 'A3');
        $sheet->getStyle("A3:{$maxCol}3")->getFont()->setBold(true);
        $sheet->getStyle("A3:{$maxCol}3")->getAlignment()->setHorizontal('center');
    }

    private function setBorder(Spreadsheet $spreadsheet, string $maxCol, int $maxRow)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A3:' . $maxCol . $maxRow)->applyFromArray($styleArray);
    }

    private function fillRow(Spreadsheet $spreadsheet, int $row, array $data)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($data, null, "A{$row}");
    }

    private function fillSheet(Spreadsheet $spreadsheet, string $title, array $header, array $data)
    {
        $maxCol = chr(64 + count($header));
        $maxRow = count($data) + 3;

        $this->setTitle($spreadsheet, $maxCol, $title);
        $this->setHeader($spreadsheet, $maxCol, $header);

        $row = 4;
        foreach ($data as $item) {
            $this->fillRow($spreadsheet, $row, $item);
            $row++;
        }

        $this->setBorder($spreadsheet, $maxCol, $maxRow);
    }
}
