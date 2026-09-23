<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $from;
    protected $to;

    public function __construct($search = null, $from = null, $to = null)
    {
        $this->search = $search;
        $this->from = $from;
        $this->to = $to;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'No HP',
            'Jenis Pelayanan',
            'Media Pelayanan',
            'Tujuan',
            'Sub Kategori',
            'Data Diminta',
            'Status Transaksi',
            'Penilaian Pelayanan',
            'Penilaian Fasilitas',
            'Penilaian Kualitas',
            'Saran',
            'Tanggal Transaksi',
            'Tanggal Dilayani',
            'Dilayani Oleh',
            'Keterangan Pelayanan',
        ];
    }

    public function collection(): Collection
    {
        $query = Transaction::with([
            'customer',
            'media',
            'service',
            'purpose',
            'subCategorie',
            'feedback',
            'dilayaniOleh',
        ])->orderBy('created_at', 'desc');

        if ($this->search) {
            $search = $this->search;

            $query->whereHas('customer', function ($sub) use ($search) {
                $sub->where('nama_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->get()->values()->map(function ($transaction, $index) {
            return [
                $index + 1,
                $transaction->customer->nama_lengkap ?? '-',
                $transaction->customer->no_hp ?? '-',
                $transaction->service->service_type ?? '-',
                $transaction->media->media_type ?? '-',
                $transaction->purpose->purpose_type ?? '-',
                $transaction->subCategorie->sub_categorie_type ?? '-',
                $transaction->data_diminta ?? '-',
                $transaction->status_transaksi ?? '-',
                $transaction->feedback->rating_pelayanan ?? '-',
                $transaction->feedback->rating_fasilitas ?? '-',
                $transaction->feedback->rating_kualitas ?? '-',
                $transaction->feedback->saran_kritik ?? '-',
                $transaction->created_at
                    ? $transaction->created_at->format('d-m-Y H:i:s')
                    : '-',
                $transaction->dilayani_pada
                    ? $transaction->dilayani_pada->format('d-m-Y H:i:s')
                    : '-',
                $transaction->dilayaniOleh->name ?? '-',
                $transaction->keterangan_pelayanan ?? '-',
            ];
        });
    }
}