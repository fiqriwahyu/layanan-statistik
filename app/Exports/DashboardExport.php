<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Education;
use App\Models\Media;
use App\Models\Purpose;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\Work;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DashboardExport implements FromArray, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function headings(): array
    {
        return ['No', 'Kategori', 'Data', 'Jumlah'];
    }

    public function array(): array
    {
        $from = $this->from;
        $to = $this->to;

        $customerQuery = Customer::query();
        $transactionQuery = Transaction::query();

        if ($from) {
            $customerQuery->whereDate('created_at', '>=', $from);
            $transactionQuery->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $customerQuery->whereDate('created_at', '<=', $to);
            $transactionQuery->whereDate('created_at', '<=', $to);
        }

        $rows = [];
        $no = 1;

        $rows[] = [$no++, 'Ringkasan', 'Total Pengunjung', $customerQuery->count()];
        $rows[] = [$no++, 'Ringkasan', 'Total Transaksi', $transactionQuery->count()];
        $rows[] = [$no++, 'Ringkasan', 'Belum Dilayani', (clone $transactionQuery)->where('status_transaksi', 'Belum dilayani')->count()];
        $rows[] = [$no++, 'Ringkasan', 'Sudah Dilayani', (clone $transactionQuery)->where('status_transaksi', 'Sudah dilayani')->count()];

        $pendidikan = Education::withCount([
            'customers' => function ($query) use ($from, $to) {
                if ($from) $query->whereDate('customers.created_at', '>=', $from);
                if ($to) $query->whereDate('customers.created_at', '<=', $to);
            }
        ])->orderBy('id')->get();

        foreach ($pendidikan as $item) {
            $rows[] = [$no++, 'Pendidikan', $item->education_type, $item->customers_count];
        }

        $pekerjaan = Work::withCount([
            'customers' => function ($query) use ($from, $to) {
                if ($from) $query->whereDate('customers.created_at', '>=', $from);
                if ($to) $query->whereDate('customers.created_at', '<=', $to);
            }
        ])->orderBy('id')->get();

        foreach ($pekerjaan as $item) {
            $rows[] = [$no++, 'Pekerjaan', $item->work_type, $item->customers_count];
        }

        $media = Media::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) $query->whereDate('transactions.created_at', '>=', $from);
                if ($to) $query->whereDate('transactions.created_at', '<=', $to);
            }
        ])->orderBy('id')->get();

        foreach ($media as $item) {
            $rows[] = [$no++, 'Media Pelayanan', $item->media_type, $item->transactions_count];
        }

        $services = Service::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) $query->whereDate('transactions.created_at', '>=', $from);
                if ($to) $query->whereDate('transactions.created_at', '<=', $to);
            }
        ])->orderBy('id')->get();

        foreach ($services as $item) {
            $rows[] = [$no++, 'Jenis Pelayanan', $item->service_type, $item->transactions_count];
        }

        $purposes = Purpose::withCount([
            'transactions' => function ($query) use ($from, $to) {
                if ($from) $query->whereDate('transactions.created_at', '>=', $from);
                if ($to) $query->whereDate('transactions.created_at', '<=', $to);
            }
        ])->orderBy('id')->get();

        foreach ($purposes as $item) {
            $rows[] = [$no++, 'Tujuan', $item->purpose_type, $item->transactions_count];
        }

        return $rows;
    }
}