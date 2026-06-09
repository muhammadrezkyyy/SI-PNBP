<?php

namespace App\Services;

class SimponiParserService
{
    /**
     * Parse extracted raw text from a SIMPONI PDF billing document.
     *
     * @param  string $rawText  Raw text content extracted from the PDF.
     * @return array{billing_code: string|null, nominal: float|null, status: string, errors: array}
     */
    public function parsePdf(string $rawText): array
    {
        $errors = [];
        $billingCode = null;
        $nominal = null;

        // --- Extract 15-digit Billing Code (Kode Billing SIMPONI) ---
        // Pattern: exactly 15 consecutive digits, with word boundaries.
        if (preg_match('/\b(\d{15})\b/', $rawText, $matches)) {
            $billingCode = $matches[1];
        } else {
            $errors[] = 'Kode billing 15 digit tidak ditemukan dalam dokumen.';
        }

        // --- Extract Nominal / Amount ---
        // Handles various Indonesian Rupiah formats:
        //   "Rp. 1.500.000", "Rp1500000", "IDR 1,500,000"
        $nominalPatterns = [
            '/Rp\.?\s*([\d.,]+)/i',
            '/IDR\.?\s*([\d.,]+)/i',
            '/([\d.,]+)\s*\(IDR\)/i', // Matches: 16.502.498 (IDR)
            '/(?:Total Disetor|Jumlah Setoran)\s*[:=]?\s*([\d.,]+)/i',
            '/JUMLAH\s*[:=]?\s*Rp\.?\s*([\d.,]+)/i',
            '/NOMINAL\s*[:=]?\s*Rp\.?\s*([\d.,]+)/i',
            '/SETORAN\s*[:=]?\s*Rp\.?\s*([\d.,]+)/i',
        ];

        foreach ($nominalPatterns as $pattern) {
            if (preg_match($pattern, $rawText, $matches)) {
                $nominal = $this->parseRupiah($matches[1]);
                break;
            }
        }

        if ($nominal === null) {
            $errors[] = 'Nominal pembayaran tidak ditemukan dalam dokumen.';
        }

        return [
            'billing_code' => $billingCode,
            'nominal'      => $nominal,
            'status'       => empty($errors) ? 'success' : 'error',
            'errors'       => $errors,
            'raw_length'   => strlen($rawText),
        ];
    }

    /**
     * Normalize Indonesian Rupiah string to float.
     * Handles "1.500.000,50" or "1,500,000.50" formats.
     */
    private function parseRupiah(string $value): float
    {
        // Remove all non-numeric characters except comma and dot
        $cleaned = preg_replace('/[^\d.,]/', '', $value);

        // Detect format: if last separator is comma → "1.000.000,50" (ID format)
        if (preg_match('/,\d{2}$/', $cleaned)) {
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
        } else {
            // Remove dots used as thousand separators
            $cleaned = str_replace(['.', ','], '', $cleaned);
        }

        return (float) $cleaned;
    }
}
