<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PdfStamperService
{
    /**
     * Disk yang digunakan untuk menyimpan PDF.
     */
    private function getStorageDisk(): string
    {
        return config('filesystems.default', 'local');
    }

    /**
     * Mengisi kolom Tanggal Bayar, Status, NTB, dan NTPN
     * pada file BPN SIMPONI asli.
     * Mendukung disk local maupun S3/R2.
     */
    public function fillSimponiBpn(string $pdfPath, array $data): bool
    {
        $disk        = $this->getStorageDisk();
        $isS3        = ($disk !== 'local');
        $tmpPath     = null;

        try {
            $storageDisk = Storage::disk($disk);

            if (! $storageDisk->exists($pdfPath)) {
                Log::warning('[PdfStamper] File tidak ditemukan di storage.', ['path' => $pdfPath, 'disk' => $disk]);
                return false;
            }

            if ($isS3) {
                // Download dari S3 ke file sementara lokal
                $tmpPath     = tempnam(sys_get_temp_dir(), 'simponi_') . '.pdf';
                file_put_contents($tmpPath, $storageDisk->get($pdfPath));
                $absolutePath = $tmpPath;
            } else {
                $absolutePath = $storageDisk->path($pdfPath);
            }

            $pdf       = new Fpdi();
            $pageCount = $pdf->setSourceFile($absolutePath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size       = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                // Jika halaman pertama, kita lakukan penimpaan (auto-fill)
                if ($pageNo === 1) {
                    $pdf->SetFont('Arial', '', 10);
                    $pdf->SetTextColor(0, 0, 0);

                    $startX = 74;

                    // --- 1. TANGGAL BAYAR ---
                    $yTanggalBayar = 75.5;
                    $pdf->SetXY($startX, $yTanggalBayar);
                    $pdf->Cell(0, 0, $data['tanggal_bayar'] ?? '', 0, 0, 'L');

                    // --- 2. STATUS ---
                    $yStatus = 117.5;
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Rect($startX, $yStatus - 3, 40, 5, 'F');
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

                    // Stamp tambahan
                    $pdf->SetFont('Arial', 'I', 8);
                    $pdf->SetTextColor(100, 100, 100);
                    $pdf->SetXY(15, 280);
                    $pdf->Cell(0, 0, 'Dokumen divalidasi otomatis oleh SI-PNBP (' . now()->format('d/m/Y H:i:s') . ')', 0, 0, 'L');
                }
            }

            if ($isS3) {
                // Output ke string, upload kembali ke S3
                $pdfContent = $pdf->Output('', 'S');
                $storageDisk->put($pdfPath, $pdfContent);
            } else {
                // Timpa file asli di local
                $pdf->Output($absolutePath, 'F');
            }

            return true;

        } catch (\Exception $e) {
            Log::error('[PdfStamper] Failed to fill BPN: ' . $e->getMessage(), [
                'path' => $pdfPath,
                'disk' => $disk,
            ]);
            return false;
        } finally {
            // Hapus temp file jika ada
            if ($tmpPath && file_exists($tmpPath)) {
                @unlink($tmpPath);
            }
        }
    }

    /**
     * Metode lama untuk backward compatibility.
     */
    public function stampPaid(string $pdfPath): bool
    {
        return $this->fillSimponiBpn($pdfPath, [
            'tanggal_bayar' => now()->format('d-m-Y H:i:s'),
            'ntb'           => '-',
            'ntpn'          => '-',
        ]);
    }
}
