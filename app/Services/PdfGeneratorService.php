<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfGeneratorService
{
    /**
     * Generate SIMPONI BPN PDF from data array (template-based).
     */
    public function generateSimponiPdf(array $data): ?string
    {
        try {
            $data['qr_content'] = 'SIMPONI-BILLING-' . ($data['kode_billing'] ?? time());

            $pdf = Pdf::loadView('pdf.simponi-bpn', ['data' => $data]);
            $pdf->setPaper('A4', 'portrait');

            $filename = 'simponi_bpn_' . time() . '_' . ($data['kode_billing'] ?? 'unknown') . '.pdf';
            $path     = 'simponi-pdfs/' . $filename;

            Storage::disk('local')->put($path, $pdf->output());

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate SIMPONI PDF: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }

    /**
     * Generate PDF from the full #bpn-paper HTML snapshot captured from the browser editor.
     * The HTML already contains: QR SVGs (rendered), logo img, watermark, all text edits.
     * We just need to wrap it in a page shell and fix image URLs for DOMPDF.
     */
    public function generateFromEditedHtml(string $paperHtml, array $simponiData = [], array $elementPositions = []): ?string
    {
        try {
            $kodeBilling = $simponiData['kode_billing'] ?? 'unknown';

            // --- 1. Fix logo URL → base64 so DOMPDF can render it ---
            $logoPath   = public_path('images/kemenkeu_logo.png');
            $logoBase64 = file_exists($logoPath)
                ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                : '';

            // Replace any src containing kemenkeu_logo with base64
            if ($logoBase64) {
                $paperHtml = preg_replace(
                    '/src="[^"]*kemenkeu_logo[^"]*"/i',
                    'src="' . $logoBase64 . '"',
                    $paperHtml
                );
                // Also handle http://127.0.0.1 or localhost URLs (browser makes them absolute)
                $paperHtml = preg_replace(
                    '/src="https?:\/\/[^"]*\/images\/kemenkeu_logo[^"]*"/i',
                    'src="' . $logoBase64 . '"',
                    $paperHtml
                );
            }

            // --- 2. Strip Alpine / Livewire / interactive attributes ---
            $paperHtml = preg_replace('/\s+wire:[^\s>]+/i', '', $paperHtml);
            $paperHtml = preg_replace('/\s+x-data[^=]*="[^"]*"/i', '', $paperHtml);
            $paperHtml = preg_replace('/\s+x-ref="[^"]*"/i', '', $paperHtml);
            $paperHtml = preg_replace('/\s+@[^\s>]+/i', '', $paperHtml);
            $paperHtml = preg_replace('/\s+contenteditable="[^"]*"/i', '', $paperHtml);
            $paperHtml = preg_replace('/\s+spellcheck="[^"]*"/i', '', $paperHtml);
            $paperHtml = preg_replace('/\s+draggable="[^"]*"/i', '', $paperHtml);

            // --- 3. Build full HTML page ---
            $fullHtml = <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="utf-8">
                <title>Bukti Penerimaan Negara</title>
                <style>
                    @page { margin: 0; size: A4; }
                    html, body { margin: 0; padding: 0; background: white; }
                    /* Hide drag handles */
                    .bpn-drag-handle { display: none !important; }
                    /* Ensure fonts match */
                    body { font-family: Arial, Helvetica, sans-serif; }
                    /* Remove interactive cursor styling */
                    * { cursor: default !important; }
                </style>
            </head>
            <body>
                {$paperHtml}
            </body>
            </html>
            HTML;

            $pdf = Pdf::loadHTML($fullHtml)->setPaper('A4', 'portrait');
            // Match the paper div size (794px wide = A4 at 96dpi)
            $pdf->getDomPDF()->set_option('dpi', 96);
            $pdf->getDomPDF()->set_option('enable_html5_parser', true);
            $pdf->getDomPDF()->set_option('isRemoteEnabled', false);

            $filename = 'simponi_bpn_edited_' . time() . '_' . $kodeBilling . '.pdf';
            $path     = 'simponi-pdfs/' . $filename;

            Storage::disk('local')->put($path, $pdf->output());

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate PDF from paper HTML: ' . $e->getMessage());
            // Fallback to template if HTML generation fails
            return $this->generateSimponiPdf($simponiData);
        }
    }
}
