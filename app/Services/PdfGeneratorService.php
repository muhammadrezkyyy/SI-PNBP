<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfGeneratorService
{
    /**
     * Generate SIMPONI BPN PDF from data array.
     *
     * @param array $data
     * @return string|null Returns the path to the saved PDF or null on failure.
     */
    public function generateSimponiPdf(array $data): ?string
    {
        try {
            // Provide a QR code payload or URL
            $data['qr_content'] = 'SIMPONI-BILLING-' . ($data['kode_billing'] ?? time());
            
            // Format nominal if needed
            if (!empty($data['total_disetor']) && is_numeric(str_replace(['.', ','], '', $data['total_disetor']))) {
                // Keep it as is if it's already a string from the input, or format it
                // We'll trust the user input since it's a manual edit.
            }

            // Generate PDF from view
            $pdf = Pdf::loadView('pdf.simponi-bpn', ['data' => $data]);
            
            // Set paper size (e.g., A4 portrait)
            $pdf->setPaper('A4', 'portrait');
            
            // Define a unique path
            $filename = 'simponi_bpn_' . time() . '_' . ($data['kode_billing'] ?? 'unknown') . '.pdf';
            $path = 'simponi-pdfs/' . $filename;
            
            // Save to local storage
            Storage::disk('local')->put($path, $pdf->output());
            
            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate SIMPONI PDF: ' . $e->getMessage(), ['data' => $data]);
            return null;
        }
    }
}
