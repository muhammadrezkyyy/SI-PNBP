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
        
        $data = [
            'kode_billing' => null,
            'tanggal_billing' => null,
            'tanggal_kedaluwarsa' => null,
            'tanggal_bayar' => null,
            'bank_pos_fintech_bayar' => null,
            'channel_bayar' => null,
            'nama_wajib_setor' => null,
            'kementerian_lembaga' => null,
            'unit_eselon_i' => null,
            'satuan_kerja' => null,
            'total_disetor' => null,
            'terbilang' => null,
            'status' => null,
            'ntb' => null,
            'ntpn' => null,
            'jenis_setoran' => null,
            'kode_akun' => null,
            'jumlah_setoran' => null,
            'keterangan' => null,
        ];

        // --- Extract 15-digit Billing Code ---
        if (preg_match('/\b(\d{15})\b/', $rawText, $matches)) {
            $data['kode_billing'] = $matches[1];
        } else {
            $errors[] = 'Kode billing 15 digit tidak ditemukan.';
        }

        // --- Extract other fields line by line or via regex ---
        // Helper function for regex extraction
        $extract = function($pattern) use ($rawText) {
            if (preg_match($pattern, $rawText, $matches)) {
                return trim($matches[1]);
            }
            return '';
        };

        $data['tanggal_billing'] = $extract('/Tanggal Billing\s*:\s*(.*)/i');
        $data['tanggal_kedaluwarsa'] = $extract('/Tanggal Kedaluwarsa\s*:\s*(.*)/i');
        $data['tanggal_bayar'] = $extract('/Tanggal Bayar\s*:\s*(.*)/i');
        $data['bank_pos_fintech_bayar'] = $extract('/Bank\/Pos\/Fintech Bayar\s*:\s*(.*)/i');
        $data['channel_bayar'] = $extract('/Channel Bayar\s*:\s*(.*)/i');
        
        // This handles cases where lines might wrap or have specific trailing text
        $data['nama_wajib_setor'] = $extract('/Nama Wajib Setor\/Wajib Bayar\s*:\s*(.*?)Kementerian/is');
        if (empty($data['nama_wajib_setor'])) {
            $data['nama_wajib_setor'] = $extract('/Nama Wajib Setor\/Wajib Bayar\s*:\s*(.*)/i');
        }
        $data['nama_wajib_setor'] = trim($data['nama_wajib_setor']);

        $data['kementerian_lembaga'] = $extract('/Kementerian\/Lembaga\s*:\s*(.*)/i');
        $data['unit_eselon_i'] = $extract('/Unit Eselon I\s*:\s*(.*)/i');
        $data['satuan_kerja'] = $extract('/Satuan Kerja\s*:\s*(.*)/i');
        $data['total_disetor'] = $extract('/Total Disetor\s*:\s*(.*)/i');
        $data['terbilang'] = $extract('/Terbilang\s*:\s*(.*)/i');
        $data['status'] = $extract('/Status\s*:\s*(.*)/i');
        $data['ntb'] = $extract('/NTB\s*:\s*(.*)/i');
        $data['ntpn'] = $extract('/NTPN\s*:\s*(.*)/i');
        $data['jenis_setoran'] = $extract('/Jenis Setoran\s*:\s*(.*)/i');
        $data['kode_akun'] = $extract('/Kode Akun\s*:\s*(.*)/i');
        $data['jumlah_setoran'] = $extract('/Jumlah Setoran\s*:\s*(.*)/i');
        $data['keterangan'] = $extract('/Keterangan\s*:\s*(.*)/i');

        // Nominal float conversion for existing system support
        $nominal = null;
        if (!empty($data['total_disetor'])) {
            $nominal = $this->parseRupiah($data['total_disetor']);
        } else {
            $nominalPatterns = [
                '/Rp\.?\s*([\d.,]+)/i',
                '/IDR\.?\s*([\d.,]+)/i',
                '/([\d.,]+)\s*\(IDR\)/i',
                '/(?:Total Disetor|Jumlah Setoran)\s*[:=]?\s*([\d.,]+)/i',
            ];
            foreach ($nominalPatterns as $pattern) {
                if (preg_match($pattern, $rawText, $matches)) {
                    $nominal = $this->parseRupiah($matches[1]);
                    break;
                }
            }
        }

        if ($nominal === null) {
            $errors[] = 'Nominal pembayaran tidak ditemukan.';
        }

        return [
            'billing_code' => $data['kode_billing'],
            'nominal'      => $nominal,
            'status'       => empty($errors) ? 'success' : 'error',
            'errors'       => $errors,
            'raw_length'   => strlen($rawText),
            'full_data'    => $data,
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
