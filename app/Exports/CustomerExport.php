<?php

namespace App\Exports;

use App\Models\Customer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
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
            'Nama Lengkap',
            'No HP',
            'Jenis Kelamin',
            'Email',
            'Pendidikan',
            'Pekerjaan',
            'Tanggal',
        ];
    }

    public function collection(): Collection
    {
        $query = Customer::with(['work', 'education'])
            ->orderBy('created_at', 'desc');

        if ($this->search) {
            $query->where(function ($sub) {
                $sub->where('nama_lengkap', 'like', '%' . $this->search . '%')
                    ->orWhere('no_hp', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->from) {
            $query->whereDate('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $query->whereDate('created_at', '<=', $this->to);
        }

        return $query->get()->values()->map(function ($customer, $index) {
            return [
                $index + 1,
                $customer->nama_lengkap,
                $customer->no_hp,
                $customer->jenis_kelamin,
                $customer->email,
                $customer->education?->education_type ?? '-',
                $customer->work?->work_type ?? '-',
                $customer->created_at?->format('d-m-Y H:i:s') ?? '-',
            ];
        });
    }
}