<?php
namespace App\Services;

use TCPDF;

class ReportService
{
    public function generatePDFReport($data)
    {
        require_once(__DIR__ . '/../../vendor/tecnickcom/tcpdf/tcpdf.php');

        $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetTitle('Reporte de Propiedades');
        $pdf->SetHeaderData('', 0, 'Reporte de Propiedades', 'Generado por: ReportService');

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();

        $html = '<h1>Propiedades encontradas:</h1>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
        $html .= '<tr>
            <th style="width: 10%;">ID</th>
            <th style="width: 70%;">Descripción</th>
            <th style="width: 10%;">Precio</th>
            <th style="width: 10%;">Metros Cuadrados</th>
        </tr>';

        foreach ($data as $property) {
            $html .= '<tr>';
            $html .= '<td style="width: 10%;">' . $property['id'] . '</td>';
            $html .= '<td style="width: 70%;">' . $property['description'] . '</td>';
            $html .= '<td style="width: 10%;">' . $property['price'] . '</td>';
            $html .= '<td style="width: 10%;">' . $property['square_meters'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $filename = 'report_' . date('YmdHis') . '.pdf';

        $pdf->Output($filename, 'D');
    }

    public function generateCSVReport($data)
    {
        $filename = 'report_' . date('YmdHis') . '.csv';

        $fp = fopen($filename, 'w');

        fputcsv($fp, array('ID', 'Descripción', 'Precio', 'Metros Cuadrados'));

        foreach ($data as $property) {
            fputcsv($fp, array($property['id'], $property['description'], $property['price'], $property['square_meters']));
        }

        fclose($fp);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);
        exit;
    }
}
?>
