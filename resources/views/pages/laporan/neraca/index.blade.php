<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
            .table-bondered th, td, th{
                padding: 8px !important;
                text-align: left !important;
                border: 1px solid #9896966e !important;
            }

        </style>
    @endpush
    @push('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        })
    </script>
    @endpush
    @section('content')
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>

        </div>
        <div class="card mb-4">
            <header class="card-header">
                <h4>WILAYAH: 01 - JAWA TIMUR</h4>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th>LEDGER A/C NO.</th>
                                <th scope="col">KETERANGAN</th>
                                <th scope="col">SALDO AWAL</th>
                                <th scope="col"> </th>
                                <th scope="col">TRANSAKSI DEBIT</th>
                                <th scope="col">TRANSAKSI KREDIT</th>
                                <th scope="col">SALDO AKHIR</th>
                                <th scope="col"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ledger as $item)
                                @php
                                    $kode_induk = \App\Models\KodeInduk::where('id_ledger',$item->id)->get();
                                @endphp
                                <tr class="table-success">
                                    <td>{{ $item->kode_ledger }}</td>
                                    <td>{{ $item->nama }}</td>

                                </tr>
                                <tr style="font-weight: bold" class="table-secondary">
                                    @foreach ($kode_induk as $item_induk)
                                        @php
                                            $kode_akun = \App\Models\KodeAkun::where('id_induk',$item_induk->id)->get();
                                        @endphp
                                        <td>{{ $item_induk->kode_induk }}</td>
                                        <td>{{ $item_induk->nama }}</td>
                                    @endforeach
                                    @foreach ($kode_akun as $item_akun)
                                        <tr>
                                            <td class="align-top">{{ $item_akun->kode_akun }}</td>
                                            <td class="align-top">{{ $item_akun->nama_akun }}</td>
                                            <td class="align-top">Belum</td>
                                            <td class="align-top">DR</td>
                                            @if ($item_akun->nama_akun == 'TABUNGAN MUDHARABAH')
                                                @php
                                                    $data_tabungan = \App\Models\TransaksiTabungan::select('transaksi_tabungan.*')
                                                                    ->get()
                                                @endphp
                                                <td class="p-0">
                                                    <table class="table">
                                                        @foreach ($data_tabungan as $itemS)
                                                        <tr>
                                                            <td class="w-100" style="border-right: none !important; border-left: none !important;"> {{ $itemS->jenis == 'keluar' ?  'Rp.'.number_format($itemS->nominal,2, ",", ".") : '-'}}</td>
                                                        </tr>
                                                            {{-- <td> {{ $itemS->jenis == 'keluar' ?  'Rp.'.number_format($itemS->nominal,2, ",", ".") : '-'}}</td> --}}
                                                        {{-- </tr> --}}

                                                        @endforeach

                                                    </table>
                                                </td>
                                                <td class="p-0">
                                                    <table class="table">
                                                        @foreach ($data_tabungan as $itemS)
                                                        <tr>
                                                            <td class="w-100" style="border-right: none !important; border-left: none !important;"> {{ $itemS->jenis == 'masuk' ?  'Rp.'.number_format($itemS->nominal,2, ",", ".") : '-'}}</td>
                                                        </tr>
                                                        {{-- </tr> --}}

                                                        @endforeach

                                                    </table>
                                                </td>

                                            @endif
                                        </tr>
                                    @endforeach
                                    {{-- {{ $kode_akun }} --}}
                                </tr>
                            @empty
                                <tr>
                                    <td>Tidak ada data</td>
                                </tr>

                            @endforelse
                            {{-- <tr>
                                <td>10000</td>
                                <td>A K T I V A</td>
                                <td>4.523.358.630.39</td>
                                <td>DR</td>
                                <td> - </td>
                                <td>100.000.00</td>
                                <td>4.523.358.630.39</td>
                                <td>DR</td>
                            </tr> --}}

                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
