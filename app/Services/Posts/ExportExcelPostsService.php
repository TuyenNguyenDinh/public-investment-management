<?php

namespace App\Services\Posts;

use App\Models\Category;
use App\Models\OrganizationUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

class ExportExcelPostsService
{
    /**
     * Generates an Excel report of posts categorized by organization units.
     *
     * This method retrieves posts associated with the current session's organization units,
     * organizes them by category, and exports the data into an Excel file using a predefined template.
     * The Excel file includes details such as post title, organizations, creation and update timestamps,
     * creator and updater names, and approval status. The resulting file is saved to storage and the path is returned.
     *
     * @return string The file path of the generated Excel report.
     * @throws Exception|\PhpOffice\PhpSpreadsheet\Exception
     */
    public function run(): string
    {
        $categories = Category::query()
            ->with(['posts']);
        $postsByCategory = $categories->get();

        $templatePath = resource_path('templates/BAO_CAO_TIN_TUC_EXCEL.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();

        $startRow = 9;

        foreach ($postsByCategory as $key => $category) {
            if (!$category->posts->toArray()) {
                break;
            }
            $sheet->getStyle("A$startRow:I$startRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $sheet->getStyle("A$startRow")->getFont()->setBold(true)->setSize(13);
            $sheet->getStyle("A$startRow:I$startRow")->getFont()->setName('Times New Roman');
            $sheet->setCellValue("A$startRow", $key + 1)
                ->getStyle("A$startRow:I$startRow")
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('FFFFFF00');
            $sheet->getStyle("A$startRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->mergeCells("B$startRow:I$startRow")
                ->setCellValue("B$startRow", $category['name']);
            $sheet->getStyle("B$startRow")->getFont()->setBold(true)->setSize(13);

            $startRow++;

            foreach ($category->posts as $index => $article) {
                $sheet->getStyle("A$startRow:I$startRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                $sheet->setCellValue("A$startRow", $index + 1);
                $sheet->setCellValue("B$startRow", $article['title']);
                $sheet->setCellValue("C$startRow", implode(', ', $article->organizations?->pluck('name')->toArray()));
                $sheet->setCellValue("D$startRow", Carbon::parse($article['created_at'])->format('d/m/Y H:i:s'));
                $sheet->setCellValue("E$startRow", User::find($article['created_by'])->name);
                $sheet->setCellValue("F$startRow", Carbon::parse($article['updated_at'])->format('d/m/Y H:i:s'));
                $sheet->setCellValue("G$startRow", User::find($article['updated_by'])->name);
                $sheet->setCellValue("H$startRow", $article['approved'] ? 'Đã đăng' : 'Bài nháp');
                $sheet->setCellValue("I$startRow", $article['approved'] ? 'Đã kích hoạt' : 'Chưa kích hoạt');

                $startRow++;
            }
        }
        $countPosts = $categories
            ->get()
            ->pluck('posts')
            ->flatten()
            ->unique('id')
            ->count();
        $fieldCountPosts = $sheet->mergeCells("A$startRow:I$startRow")
            ->setCellValue("A$startRow", "Danh sách này tổng cộng có " . $countPosts . " tin tức")
            ->getStyle("A$startRow");
        $fieldCountPosts->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $fieldCountPosts->getFont()->setItalic(true)->setBold(true);

        $outputPath = storage_path('app/public/' . time() . '_BAO_CAO_TIN_TUC_EXCEL.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputPath);

        return $outputPath;
    }
}
