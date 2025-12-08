<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Asuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    // Export kunjungan CSV filtered by poli (ugd, umum, rawat_inap)
    public function kunjunganCsv(Request $request, $poli)
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $query = Kunjungan::with('patient')->where('poli_tujuan', $poli);
        if ($from) $query->where('tanggal_kunjungan', '>=', $from);
        if ($to) $query->where('tanggal_kunjungan', '<=', $to);

        $rows = $query->orderBy('tanggal_kunjungan', 'desc')->get();

        $filename = "kunjungan_{$poli}_" . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No RM','Nama Pasien','Tanggal Kunjungan','Poli Tujuan','Jenis Bayar','Catatan']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->patient->no_rm ?? '-',
                    $r->patient->nama_pasien ?? '-',
                    optional($r->tanggal_kunjungan)->format('Y-m-d') ?? $r->tanggal_kunjungan,
                    $r->poli_tujuan,
                    $r->jenis_bayar ?? '-',
                    $r->catatan_kunjungan ?? '-',
                ]);
            }
            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    // Export farmasi CSV (prescriptions)
    public function farmasiCsv(Request $request)
    {
        $rows = Asuhan::with('patient')
            ->whereNotNull('resep')
            ->orderBy('tanggal_asuhan', 'desc')
            ->get();

        $filename = 'farmasi_resep_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No RM','Nama Pasien','Tanggal Asuhan','Poli','Resep','Sudah Diambil']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->patient->no_rm ?? '-',
                    $r->patient->nama_pasien ?? '-',
                    optional($r->tanggal_asuhan)->format('Y-m-d'),
                    $r->poli_tujuan,
                    strip_tags($r->resep),
                    $r->resep_collected_at ? $r->resep_collected_at->format('Y-m-d H:i') : '',
                ]);
            }
            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }
}
