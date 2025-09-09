<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $pemasukan = Pemasukan::select('keterangan_pemasukan', 'nominal_pemasukan', 'tanggal_pemasukan')->get();
        $pengeluaran = Pengeluaran::select('keterangan_pengeluaran', 'nominal_pengeluaran', 'tanggal_pengeluaran')->get();

        $laporan = [];

        foreach ($pemasukan as $p) {
            $laporan[] = [
                'Jenis' => 'Pemasukan',
                'Keterangan' => $p->keterangan_pemasukan,
                'Nominal' => $p->nominal_pemasukan,
                'Tanggal' => $p->tanggal_pemasukan,
            ];
        }

        foreach ($pengeluaran as $p) {
            $laporan[] = [
                'Jenis' => 'Pengeluaran',
                'Keterangan' => $p->keterangan_pengeluaran,
                'Nominal' => $p->nominal_pengeluaran,
                'Tanggal' => $p->tanggal_pengeluaran,
            ];
        }

        return new Collection($laporan);
    }

    public function headings(): array
    {
        return ['Jenis', 'Keterangan', 'Nominal', 'Tanggal'];
    }
}

