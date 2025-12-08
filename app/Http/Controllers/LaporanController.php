<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Asuhan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display kunjungan report
     */
    public function kunjungan(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $poli = $request->input('poli', '');
        
        $query = Kunjungan::with('patient');
        
        // Filter by month
        if ($month) {
            $year = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            $query->whereYear('tanggal_kunjungan', $year)
                  ->whereMonth('tanggal_kunjungan', $monthNum);
        }
        
        // Filter by poli
        if ($poli && in_array($poli, ['ugd', 'umum', 'rawat_inap'])) {
            $query->where('poli_tujuan', $poli);
        }
        
        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();
        $monthLabel = Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');
        
        return view('pages.laporan.kunjungan', compact('kunjungans', 'month', 'poli', 'monthLabel'));
    }

    /**
     * Export kunjungan to Excel
     */
    public function exportKunjungan(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $poli = $request->input('poli', '');
        
        $query = Kunjungan::with('patient');
        
        // Filter by month
        if ($month) {
            $year = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            $query->whereYear('tanggal_kunjungan', $year)
                  ->whereMonth('tanggal_kunjungan', $monthNum);
        }
        
        // Filter by poli
        if ($poli && in_array($poli, ['ugd', 'umum', 'rawat_inap'])) {
            $query->where('poli_tujuan', $poli);
        }
        
        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();
        
        // Create Excel export
        // Export as CSV (Excel can open CSV). Use .csv extension to avoid format/extension mismatch.
        $filename = 'Laporan_Kunjungan_' . $month . '.csv';
        return $this->generateKunjunganExcel($kunjungans, $filename);
    }

    /**
     * Display prescription report
     */
    public function resep(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $status = $request->input('status', '');
        
                // include Asuhan that either have a legacy 'resep' text OR normalized prescriptionItems
                $query = Asuhan::with(['patient', 'user', 'prescriptionItems'])
                                             ->where(function($q) {
                                                     $q->whereNotNull('resep')->where('resep', '!=', '')
                                                         ->orWhereHas('prescriptionItems');
                                             });
        
        // Filter by month
        if ($month) {
            $year = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            $query->whereYear('tanggal_asuhan', $year)
                  ->whereMonth('tanggal_asuhan', $monthNum);
        }
        
        // Filter by status (diambil / belum diambil)
        if ($status === 'diambil') {
            $query->whereNotNull('resep_collected_at');
        } elseif ($status === 'belum') {
            $query->whereNull('resep_collected_at');
        }
        
        $reseps = $query->orderBy('tanggal_asuhan', 'desc')->get();
        $monthLabel = Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');
        
        return view('pages.laporan.resep', compact('reseps', 'month', 'status', 'monthLabel'));
    }

    /**
     * Export resep to Excel
     */
    public function exportResep(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $status = $request->input('status', '');
        
                $query = Asuhan::with(['patient', 'user', 'prescriptionItems'])
                                             ->where(function($q) {
                                                     $q->whereNotNull('resep')->where('resep', '!=', '')
                                                         ->orWhereHas('prescriptionItems');
                                             });
        
        // Filter by month
        if ($month) {
            $year = substr($month, 0, 4);
            $monthNum = substr($month, 5, 2);
            $query->whereYear('tanggal_asuhan', $year)
                  ->whereMonth('tanggal_asuhan', $monthNum);
        }
        
        // Filter by status (diambil / belum diambil)
        if ($status === 'diambil') {
            $query->whereNotNull('resep_collected_at');
        } elseif ($status === 'belum') {
            $query->whereNull('resep_collected_at');
        }
        
        $reseps = $query->orderBy('tanggal_asuhan', 'desc')->get();
        
        // Generate Excel response
        // Export as CSV (Excel-friendly). Use .csv extension.
        $filename = 'Laporan_Resep_' . $month . '.csv';
        return $this->generateResepExcel($reseps, $filename);
    }

    /**
     * Generate Excel for Kunjungan
     */
    private function generateKunjunganExcel($kunjungans, $filename)
    {
        // Prefer generating real XLSX if PhpSpreadsheet is available
            if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->fromArray(['No', 'No RM', 'Nama Pasien', 'Tanggal Kunjungan', 'Poli Tujuan'], null, 'A1');

            $row = 2;
            foreach ($kunjungans as $index => $item) {
                try {
                    $tanggal = \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d-m-Y H:i');
                } catch (\Throwable $e) {
                    $tanggal = is_string($item->tanggal_kunjungan) ? $item->tanggal_kunjungan : '-';
                }

                $sheet->setCellValue("A{$row}", $index + 1);
                $sheet->setCellValue("B{$row}", $item->patient->no_rm ?? '-');
                $sheet->setCellValue("C{$row}", $item->patient->nama_pasien ?? '-');
                $sheet->setCellValue("D{$row}", $tanggal);
                $sheet->setCellValue("E{$row}", ucfirst(str_replace('_', ' ', $item->poli_tujuan)));
                $row++;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            // Use a persistent temp file path (tempnam) so it remains available when the stream is sent,
            // then remove it after streaming to avoid leftover files.
            $tmpPath = tempnam(sys_get_temp_dir(), 'laporan_') . '.xlsx';
            $writer->save($tmpPath);

            $downloadName = pathinfo($filename, PATHINFO_FILENAME) . '.xlsx';

            return response()->stream(function() use ($tmpPath) {
                readfile($tmpPath);
                @unlink($tmpPath);
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename={$downloadName}",
            ]);
        }

        // Fallback to CSV if PhpSpreadsheet is not installed
        $headers = array(
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Expires" => "0",
        );

        $callback = function() use ($kunjungans) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel to recognize UTF-8 encoding
            fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'No RM', 'Nama Pasien', 'Tanggal Kunjungan', 'Poli Tujuan']);
            
            // Data
            foreach ($kunjungans as $index => $item) {
                $tanggal = null;
                try {
                    $tanggal = \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d-m-Y H:i');
                } catch (\Throwable $e) {
                    $tanggal = is_string($item->tanggal_kunjungan) ? $item->tanggal_kunjungan : '-';
                }

                fputcsv($file, [
                    $index + 1,
                    $item->patient->no_rm ?? '-',
                    $item->patient->nama_pasien ?? '-',
                    $tanggal,
                    ucfirst(str_replace('_', ' ', $item->poli_tujuan)),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate Excel for Resep
     */
    private function generateResepExcel($reseps, $filename)
    {
        // Prefer generating real XLSX if PhpSpreadsheet is available
        if (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->fromArray(['No', 'No RM', 'Nama Pasien', 'Poli', 'Tanggal', 'Detail Obat', 'Total Harga', 'Status', 'Tanggal Diambil'], null, 'A1');

            $row = 2;
            foreach ($reseps as $index => $item) {
                try {
                    $tgl = \Carbon\Carbon::parse($item->tanggal_asuhan)->format('d-m-Y');
                } catch (\Throwable $e) {
                    $tgl = is_string($item->tanggal_asuhan) ? $item->tanggal_asuhan : '-';
                }

                if ($item->resep_collected_at) {
                    try {
                        $collected = \Carbon\Carbon::parse($item->resep_collected_at)->format('d-m-Y H:i');
                    } catch (\Throwable $e) {
                        $collected = is_string($item->resep_collected_at) ? $item->resep_collected_at : '-';
                    }
                } else {
                    $collected = '-';
                }

                $detail = '';
                $total = '';
                if (isset($item->prescriptionItems) && $item->prescriptionItems->isNotEmpty()) {
                    $parts = [];
                    $sum = 0;
                    foreach ($item->prescriptionItems as $it) {
                        $parts[] = ($it->name ?? 'Unknown') . ' x' . ($it->qty ?? 1) . (isset($it->price) && $it->price ? ' - Rp ' . number_format($it->price,0,',','.') : '');
                        $p = isset($it->price) ? floatval($it->price) : 0;
                        $q = isset($it->qty) ? intval($it->qty) : 0;
                        $sum += ($p * $q);
                    }
                    $detail = implode('; ', $parts);
                    $total = $sum;
                } else {
                    $detail = str_replace(["\r", "\n"], " ", $item->resep);
                    $total = !empty($item->resep_total) ? $item->resep_total : '';
                }

                $sheet->setCellValue("A{$row}", $index + 1);
                $sheet->setCellValue("B{$row}", $item->patient->no_rm ?? '-');
                $sheet->setCellValue("C{$row}", $item->patient->nama_pasien ?? '-');
                $sheet->setCellValue("D{$row}", ucfirst(str_replace('_', ' ', $item->poli_tujuan)));
                $sheet->setCellValue("E{$row}", $tgl);
                $sheet->setCellValue("F{$row}", $detail);
                $sheet->setCellValue("G{$row}", $total !== '' ? ('Rp ' . number_format($total,0,',','.')) : '');
                $sheet->setCellValue("H{$row}", $item->resep_collected_at ? 'Sudah Diambil' : 'Belum Diambil');
                $sheet->setCellValue("I{$row}", $collected);
                $row++;
            }

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $tmpPath = tempnam(sys_get_temp_dir(), 'laporan_') . '.xlsx';
            $writer->save($tmpPath);

            $downloadName = pathinfo($filename, PATHINFO_FILENAME) . '.xlsx';

            return response()->stream(function() use ($tmpPath) {
                readfile($tmpPath);
                @unlink($tmpPath);
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename={$downloadName}",
            ]);
        }

        // Fallback CSV
        $headers = array(
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Expires" => "0",
        );

        $callback = function() use ($reseps) {
            $file = fopen('php://output', 'w');
            // BOM so Excel detects UTF-8
            fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Header
            fputcsv($file, ['No', 'No RM', 'Nama Pasien', 'Poli', 'Tanggal', 'Detail Obat', 'Total Harga', 'Status', 'Tanggal Diambil']);
            
            // Data
            foreach ($reseps as $index => $item) {
                // tanggal_asuhan
                try {
                    $tgl = \Carbon\Carbon::parse($item->tanggal_asuhan)->format('d-m-Y');
                } catch (\Throwable $e) {
                    $tgl = is_string($item->tanggal_asuhan) ? $item->tanggal_asuhan : '-';
                }

                // resep_collected_at
                if ($item->resep_collected_at) {
                    try {
                        $collected = \Carbon\Carbon::parse($item->resep_collected_at)->format('d-m-Y H:i');
                    } catch (\Throwable $e) {
                        $collected = is_string($item->resep_collected_at) ? $item->resep_collected_at : '-';
                    }
                } else {
                    $collected = '-';
                }

                // Build resep/detail string and total
                $detail = '';
                $total = '';
                if (isset($item->prescriptionItems) && $item->prescriptionItems->isNotEmpty()) {
                    $parts = [];
                    $sum = 0;
                    foreach ($item->prescriptionItems as $it) {
                        $parts[] = ($it->name ?? 'Unknown') . ' x' . ($it->qty ?? 1) . (isset($it->price) && $it->price ? ' - Rp ' . number_format($it->price,0,',','.') : '');
                        $p = isset($it->price) ? floatval($it->price) : 0;
                        $q = isset($it->qty) ? intval($it->qty) : 0;
                        $sum += ($p * $q);
                    }
                    $detail = implode('; ', $parts);
                    $total = $sum;
                } else {
                    $detail = str_replace(["\r", "\n"], " ", $item->resep);
                    $total = !empty($item->resep_total) ? $item->resep_total : '';
                }

                fputcsv($file, [
                    $index + 1,
                    $item->patient->no_rm ?? '-',
                    $item->patient->nama_pasien ?? '-',
                    ucfirst(str_replace('_', ' ', $item->poli_tujuan)),
                    $tgl,
                    $detail,
                    $total !== '' ? ('Rp ' . number_format($total,0,',','.')) : '',
                    $item->resep_collected_at ? 'Sudah Diambil' : 'Belum Diambil',
                    $collected,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
