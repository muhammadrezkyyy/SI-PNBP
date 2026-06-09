<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfStamperService
{
    /**
     * Mengisi kolom Tanggal Bayar, Status, NTB, dan NTPN
     * pada file BPN SIMPONI asli.
     */
    public function fillSimponiBpn(string $pdfPath, array $data): bool
    {
        try {
            $disk = Storage::disk('local');
            
            if (!$disk->exists($pdfPath)) {
                return false;
            }

            $absolutePath = $disk->path($pdfPath);
            
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($absolutePath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                // Jika halaman pertama, kita lakukan penimpaan (auto-fill)
                if ($pageNo === 1) {
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetTextColor(0, 0, 0); // Hitam

                    // Estimasi kordinat X untuk nilai (setelah titik dua ': ')
                    $startX = 74; 
                    
                    // --- 1. TANGGAL BAYAR ---
                    // Estimasi Y untuk Tanggal Bayar (biasanya baris ke-4 di Data Pembayaran Tagihan)
                    $yTanggalBayar = 75.5; 
                    $pdf->SetXY($startX, $yTanggalBayar);
                    $pdf->Cell(0, 0, $data['tanggal_bayar'] ?? '', 0, 0, 'L');

                    // --- 2. STATUS (Timpa "Belum Dibayar" dengan "Sudah Dibayar") ---
                    $yStatus = 117.5;
                    // Gambar kotak putih untuk menghapus teks "Belum Dibayar"
                    $pdf->SetFillColor(255, 255, 255);
                    // x, y, width, height, 'F' (Fill) -> y digeser sedikit karena Cell/Text hitungannya baseline
                    $pdf->Rect($startX, $yStatus - 3, 40, 5, 'F');
                    
                    // Cetak "Sudah Dibayar"
                    $pdf->SetXY($startX, $yStatus);
                    $pdf->Cell(0, 0, 'Sudah Dibayar', 0, 0, 'L');

                    // --- 3. NTB ---
                    $yNtb = 122;
                    $pdf->SetXY($startX, $yNtb);
                    $pdf->Cell(0, 0, $data['ntb'] ?? '', 0, 0, 'L');

                    // --- 4. NTPN ---
                    $yNtpn = 126.5;
                    $pdf->SetXY($startX, $yNtpn);
                    $pdf->Cell(0, 0, $data['ntpn'] ?? '', 0, 0, 'L');
                    
                    // -- Stamp tambahan (opsional) agar admin tahu ini digenerate sistem --
                    $pdf->SetFont('Arial', 'I', 8);
                    $pdf->SetTextColor(100, 100, 100);
                    $pdf->SetXY(15, 280);
                    $pdf->Cell(0, 0, 'Dokumen divalidasi otomatis oleh SI-PNBP (' . now()->format('d/m/Y H:i:s') . ')', 0, 0, 'L');
                }
            }

            // Timpa file asli
            $pdf->Output($absolutePath, 'F');
            
            return true;
        } catch (\Exception $e) {
            Log::error('[PdfStamper] Failed to fill BPN: ' . $e->getMessage(), [
                'path' => $pdfPath
            ]);
            return false;
        }
    }

    /**
     * Metode lama untuk backward compatibility (jika masih dipanggil di tempat lain)
     */
    public function stampPaid(string $pdfPath): bool
    {
        return $this->fillSimponiBpn($pdfPath, [
            'tanggal_bayar' => now()->format('d-m-Y H:i:s'),
            'ntb'           => '-',
            'ntpn'          => '-'
        ]);
    }
}
