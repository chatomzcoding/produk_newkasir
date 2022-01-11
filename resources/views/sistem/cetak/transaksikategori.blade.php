<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Transaksi</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
</head>
<body>
	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Data Transaksi <br>
                {{ $data['info'] }} <br>
                {{ $data['sesi'] }}
            </h4>
            <hr>
        </section>
        <table class="table table-bordered table-striped">
            <thead class="text-center">
              <tr>
                <th rowspan="2">NO</th>
                <th rowspan="2">KODE</th>
                <th rowspan="2">NAMA</th>
                <th rowspan="2">HARGA</th>
                <th colspan="4">DETAIL</th>
              </tr>
              <tr>
                <th>TERJUAL</th>
                <th>PENJUALAN</th>
                <th>HPP</th>
                <th>LABA</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($dtransaksi as $item)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item[0]['kode_barang'] }}</td>
                    <td>{{ ucwords($item[0]['nama_barang']) }}</td>
                    @php
                        $detail = detailtransaksikategori($item)
                    @endphp
                      <td class="text-right">
                        @for ($i = 0; $i < count($detail['harga']); $i++)
                            {{ norupiah($detail['harga'][$i]) }} <br>
                        @endfor
                      </td>
                      <td class="text-center">{{ $detail['terjual'] }}</td>
                      <td  class="text-right">{{ norupiah($detail['penjualan']) }}</td>
                      <td class="text-right">{{ norupiah($detail['hpp']) }}</td>
                      <td class="text-right">{{ norupiah($detail['laba']) }}</td>
                  </tr>
              @endforeach
              @php
                  $statistik = statistiklaporantransaksikategori($dtransaksi);
              @endphp
              <tr>
                  <th colspan="4">TOTAL</th>
                  <th>{{ $statistik['terjual'] }}</th>
                  <th class="text-right">{{ norupiah($statistik['penjualan']) }}</th>
                  <th class="text-right">{{ norupiah($statistik['hpp']) }}</th>
                  <th class="text-right">{{ norupiah($statistik['laba']) }}</th>
              </tr>
            </tbody>
          </table>
	</div>
</body>
</html>