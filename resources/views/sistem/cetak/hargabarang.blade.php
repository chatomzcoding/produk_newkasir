<!DOCTYPE html>
<html>
<head>
	<title>Cetak Data Harga Barang</title>
    <link rel="stylesheet" href="{{ asset('css/cetak.css') }}">
    <style>
        h4{
            padding: 0;
        }
    </style>
</head>
<body>
  @include('sistem.cetak.header')

	<div class="container-fluid">
		<section class="text-center">
            <h4>
                Daftar Harga Barang<br>
                kategori : {{ ucwords(DbCikara::namaKategori($kategori)) }}
            </h4>
        </section>
        <section>
            <table cellspacing="4" cellpadding="4" style="border: none" width="100%">
                @php
                    $batas = 1;
                    $batasatas = 1;
                    $batasbawah = 3;
                @endphp
                @foreach ($barang as $item)
                @if ($batas == $batasatas)
                    @php
                        $batasatas = $batasatas + 3;
                    @endphp                    
                    <tr>
                @endif
                    <td>
                        <strong> {{ strtoupper($main['client']->nama_toko) }}</strong><hr style="margin: 0px;">
                        <div>
                            @php
                                $satuan     = DbCikara::namaKategori($item->satuan_id);
                                if($satuan == 'lainnya') {
                                    $satuan = '@';
                                }
                            @endphp
                            Harga 1 {{ $satuan }} <span style="float: right"><strong>{{ norupiah($item->harga_jual) }}</strong></span>
                        </div>
                        <div class="text-center" style="padding-top: 10px">
                            <small>{{ $item->kode_barcode }}</small> <br>
                            <div style="padding-top: 5px">
                                <small style="font-size: 10px">{{ ucwords($item->nama_barang) }}</small>
                            </div>
                        </div>
                    </td>
                    @if (count($barang) == 1)
                        <td width="33%" style="border: 0px solid white"></td>
                        <td width="33%" style="border: 0px solid white"></td>
                    @endif
                    @if (count($barang) == 2 AND $batas == 2)
                        <td width="33%" style="border: 0px solid white"></td>
                    @endif
                    @if ($batas == $batasbawah)
                    @php
                        $batasbawah = $batasbawah + 3;
                    @endphp  
                        </tr>
                    @endif
                @php
                    $batas++
                @endphp
                @endforeach
            </table>
        </section>
	</div>
</body>
</html>

