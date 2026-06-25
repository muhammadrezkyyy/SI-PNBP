<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfGeneratorService
{
    /**
     * Disk yang digunakan untuk menyimpan PDF.
     * Di Railway (production) → 's3' (Cloudflare R2 / AWS S3)
     * Di lokal → 'local'
     */
    private function getStorageDisk(): string
    {
        return config('filesystems.default', 'local');
    }

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

            Storage::disk($this->getStorageDisk())->put($path, $pdf->output());

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate SIMPONI PDF: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }
}
