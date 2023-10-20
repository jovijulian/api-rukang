<table style="table-layout: fixed; width: 985px">
    <colgroup>
        <col style="width: 62.2px">
        <col style="width: 81.2px">
        <col style="width: 91.2px">
        <col style="width: 91.2px">
        <col style="width: 91.2px">
        <col style="width: 68.2px">
        <col style="width: 68.2px">
        <col style="width: 68.2px">
        <col style="width: 68.2px">
        <col style="width: 11.2px">
        <col style="width: 11.2px">
        <col style="width: 68.2px">
        <col style="width: 68.2px">
        <col style="width: 68.2px">
        <col style="width: 68.2px">
    </colgroup>
    <thead>
        <tr>
            <th colspan="2" rowspan="3"></th>
            <th colspan="3">PT. SILUET NYOMAN NUARTA</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="3">Setraduta Raya No. L-6, Sarijadi Bandung 40559</th>
            <th></th>
            <th></th>
            <th colspan="8" rowspan="2">SURAT JALAN&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        </tr>
        <tr>
            <th colspan="3">T.62 22 2020414 email:siluet@nyomanuarta.com</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>Kepada</td>
            <td colspan="4" rowspan="5">{{ $receiver }}</td>
            <td></td>
            <td colspan="2">NOMOR</td>
            <td></td>
            <td>:</td>
            <td colspan="4">{{ $nomor_travel }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">TANGGAL</td>
            <td></td>
            <td>:</td>
            <td colspan="4">{{ date('d-M-Y', strtotime($travel_date)) }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">KENDARAAN</td>
            <td></td>
            <td>:</td>
            <td colspan="4">{{ $shipping_name }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">NO. POLISI</td>
            <td></td>
            <td>:</td>
            <td colspan="4">{{ $number_plate }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">NAMA PENGEMUDI</td>
            <td></td>
            <td>:</td>
            <td colspan="4">{{ $driver_name }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">NO TELP PENGEMUDI</td>
            <td></td>
            <td>:</td>
            <td colspan="4">{{ $driver_telp }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td rowspan="3">NO</td>
            <td colspan="5" rowspan="3">NAMA BARANG</td>
            <td rowspan="3">JUMLAH</td>
            <td rowspan="3">SATUAN</td>
            <td colspan="2" rowspan="3">PACKING</td>
            <td colspan="5" rowspan="3">KETERANGAN</td>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>
        @php
            $no = 1;
        @endphp
        @foreach ($products as $product)
            <tr>
                <td>{{ $no++ }}</td>
                <td colspan="5">{{ $product['segment'] }}</td>
                <td>{{ $product['qty'] }}</td>
                <td></td>
                <td colspan="2"></td>
                <td colspan="5">{{ $product['description'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td> </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td colspan="3" rowspan="2">Dikirim oleh :</td>
            <td colspan="2" rowspan="2"> Diperiksa oleh :</td>
            <td colspan="2" rowspan="2"> Diketahui oleh :</td>
            <td colspan="3" rowspan="2">Disetujui oleh :</td>
            <td colspan="3" rowspan="2">Pengemudi : </td>
            <td colspan="2" rowspan="2">Diterima oleh :</td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td colspan="3" rowspan="4"> </td>
            <td rowspan="4"> <br><br><br> </td>
            <td rowspan="4"> </td>
            <td colspan="2" rowspan="4"> </td>
            <td colspan="3" rowspan="4"> &nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;<br>&nbsp;&nbsp;<br> &nbsp;&nbsp;</td>
            <td colspan="3" rowspan="4"></td>
            <td colspan="2" rowspan="4"></td>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
            <td colspan="3">{{ $from }}</td>
            <td>{{ $checked_by_gudang }}</td>
            <td>{{ $checked_by_keamanan }}</td>
            <td colspan="2">{{ $checked_by_produksi }}</td>
            <td colspan="3">{{ $checked_by_project_manager }}</td>
            <td colspan="3">{{ $driver }}</td>
            <td colspan="2">{{ $received_by_site_manager }}</td>
        </tr>
        <tr>
            <td colspan="3">Pengiriman</td>
            <td>Gudang</td>
            <td>Keamanan</td>
            <td colspan="2">Produksi</td>
            <td colspan="3">Project Manager</td>
            <td colspan="3">Driver</td>
            <td colspan="2">Site Manager</td>
        </tr>
    </tbody>
</table>
