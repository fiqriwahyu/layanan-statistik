<x-app-layout>

    <div class="customer-detail">

        <div class="customer-card">

            {{-- HEADER --}}
            <div class="customer-header">
                <h2>Pengunjung</h2>
            </div>

            {{-- BODY --}}
            <div class="customer-body">

                <div class="customer-row">
                    <div class="customer-label">Nama Tamu</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->nama_lengkap ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Telepon</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->no_hp ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Jenis Kelamin</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->jenis_kelamin ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Email</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->email ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Alamat</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->alamat ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Usia</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->usia ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Pendidikan</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->education->education_type ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Pekerjaan</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->work->work_type ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">NIP/NIM</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->nip_nim ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Nama Instansi</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->nama_instansi ?? '-' }}
                    </div>
                </div>

                <div class="customer-row">
                    <div class="customer-label">Dibuat Tanggal</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->created_at
                            ? $customer->created_at->format('d-m-Y H:i')
                            : '-' }}
                    </div>
                </div>

                <div class="customer-row last">
                    <div class="customer-label">Diperbarui Tanggal</div>
                    <div class="customer-colon">:</div>
                    <div class="customer-value">
                        {{ $customer->updated_at
                            ? $customer->updated_at->format('d-m-Y H:i')
                            : '-' }}
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="customer-footer">

                    <a href="{{ route('admin.customer.index') }}">
                        Kembali
                    </a>

                </div>

            </div>

        </div>

    </div>


    <style>

        /* =========================================
           DESKTOP
        ========================================= */

        .customer-detail {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }

        .customer-card {
            width: 100%;
            max-width: 100%;
            border: 1px solid #9ca3af;
            background: #fff;
            border-radius: 2px;
            box-sizing: border-box;
            overflow: hidden;
        }

        .customer-header {
            min-height: 64px;
            display: flex;
            align-items: center;
            padding: 12px 30px;
            background: #f3f3f3;
            border-bottom: 1px solid #d1d1d1;
            box-sizing: border-box;
        }

        .customer-header h2 {
            margin: 0;
            font-size: 24px;
            line-height: 28px;
            font-weight: 400;
            color: #5f6368;
        }

        .customer-body {
            width: 100%;
            padding: 0 30px 18px;
            box-sizing: border-box;
        }

        .customer-row {
            display: grid;
            grid-template-columns: 38% 28px minmax(0, 1fr);
            align-items: center;
            min-height: 43px;
            border-bottom: 1px solid #9ca3af;
            color: #5f6368;
            font-size: 16px;
            line-height: 20px;
            font-weight: 400;
            box-sizing: border-box;
        }

        .customer-label {
            min-width: 0;
            padding-right: 10px;
            overflow-wrap: anywhere;
        }

        .customer-colon {
            text-align: center;
            width: 28px;
        }

        .customer-value {
            min-width: 0;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .customer-row.last {
            border-bottom: none;
        }

        .customer-footer {
            padding-top: 18px;
        }

        .customer-footer a {
            display: inline-block;
            padding: 7px 13px;
            background: #b9d9f7;
            color: #416789;
            font-size: 14px;
            line-height: 18px;
            border-radius: 2px;
            text-decoration: none;
        }

        .customer-footer a:hover {
            background: #a9cef0;
        }


        /* =========================================
           TABLET
        ========================================= */

        @media (max-width: 900px) {

            .customer-detail {
                padding: 16px;
            }

            .customer-header {
                padding: 12px 24px;
            }

            .customer-body {
                padding: 0 24px 16px;
            }

            .customer-row {
                grid-template-columns:
                    minmax(180px, 35%)
                    24px
                    minmax(0, 1fr);
                min-height: 42px;
            }

            .customer-colon {
                width: 24px;
            }

        }


        /* =========================================
           HP
        ========================================= */

        @media (max-width: 640px) {

            .customer-detail {
                padding: 10px;
            }

            .customer-card {
                width: 100%;
            }

            .customer-header {
                min-height: 58px;
                padding: 10px 16px;
            }

            .customer-header h2 {
                font-size: 22px;
                line-height: 26px;
            }

            .customer-body {
                padding: 0 16px 14px;
            }

            .customer-row {
                grid-template-columns:
                    minmax(110px, 36%)
                    20px
                    minmax(0, 1fr);

                min-height: 42px;

                font-size: 15px;
                line-height: 19px;
            }

            .customer-label {
                padding-right: 5px;
            }

            .customer-colon {
                width: 20px;
            }

            .customer-value {
                padding-left: 2px;
            }

            .customer-footer {
                padding-top: 15px;
            }

            .customer-footer a {
                padding: 7px 13px;
                font-size: 14px;
            }

        }


        /* =========================================
           HP KECIL
        ========================================= */

        @media (max-width: 400px) {

            .customer-detail {
                padding: 8px;
            }

            .customer-header {
                padding: 10px 14px;
            }

            .customer-header h2 {
                font-size: 20px;
                line-height: 24px;
            }

            .customer-body {
                padding: 0 14px 12px;
            }

            .customer-row {
                grid-template-columns:
                    minmax(95px, 38%)
                    18px
                    minmax(0, 1fr);

                min-height: 40px;

                font-size: 14px;
                line-height: 18px;
            }

            .customer-label {
                padding-right: 4px;
            }

            .customer-colon {
                width: 18px;
            }

            .customer-value {
                padding-left: 1px;
            }

            .customer-footer {
                padding-top: 14px;
            }

            .customer-footer a {
                font-size: 14px;
                padding: 6px 12px;
            }

        }


        /* =========================================
           HP SANGAT KECIL
        ========================================= */

        @media (max-width: 320px) {

            .customer-detail {
                padding: 6px;
            }

            .customer-header {
                padding: 9px 12px;
            }

            .customer-body {
                padding-left: 12px;
                padding-right: 12px;
            }

            .customer-row {
                grid-template-columns:
                    minmax(85px, 37%)
                    16px
                    minmax(0, 1fr);

                font-size: 13px;
                line-height: 17px;
            }

            .customer-colon {
                width: 16px;
            }

        }

    </style>

</x-app-layout>