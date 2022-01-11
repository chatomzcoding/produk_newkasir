<?php

if (! function_exists('avatar')) {
    function avatar($user)
    {
        if (is_null($user->profile_photo_path)) {
            $link    = 'img/avatar.png'; 
        } else {
            $link = 'public/img/user/'.$user->profile_photo_path;
            if (!file_exists($link)) {
               $link    = 'img/avatar.png'; 
            } else {
                $link   = 'img/user/'.$user->profile_photo_path;
            }
        }
        
        return $link;
    }
}


if (! function_exists('css_statistik')) {
    function css_statistik($data)
    {
        $result = NULL;
        if ($data == '') {
            $result = 'font-weight-bold table-info';
        }
        return $result;
    }
}

if (! function_exists('custom_notif')) {
    function custom_notif($error)
    {
        switch ($error) {
            case 'The email has already been taken.':
                $result = 'Maaf, email sudah digunakan';
                break;
            case 'The no kk has already been taken.':
                $result = 'Maaf, Nomor KK sudah digunakan';
                break;
            
            default:
                $result = $error;
                break;
        }
        return $result;
    }
}
if (! function_exists('filter_data_get')) {
    function filter_data_get($get,$data)
    {
        $result     = FALSE;
        if (is_array($get)) {
            $index_a    = 0;
            $look       = 0; // tanda kebenaran
            foreach ($get as $index => $value) {
                // cek jika field tidak ada
                if (isset($_GET[$index]) AND isset($data[$index_a])) {
                    if ($index == 'tanggal') {
                        $d_tanggal = explode(' ',$data[$index_a]);
                        if ($_GET[$index] == $d_tanggal[0] || (empty($_GET[$index]) || $_GET[$index] == 'semua')) {
                            $look++;
                        }
                    } else {
                        if ($_GET[$index] == $data[$index_a] || $_GET[$index] == 'semua') {
                            $look++;
                        }
                    }
                    
                } else {
                    $look++;
                }
                $index_a++;
            }
            if ($look == count($get)) {
                $result = TRUE;
            }
        } else {
            $result = TRUE;
        }
        return $result;
    }
}

if (! function_exists('get_filter')) {
    function get_filter($data)
    {
        $result = [];
        for ($i=0; $i < count($data); $i++) { 
            $filter = (isset($_GET[$data[$i]])) ? $_GET[$data[$i]] : 'semua' ;
            $result[$data[$i]] = $filter;
        }
        return $result;
    }
}
if (! function_exists('totalpembayaran')) {
    function totalpembayaran($keranjang)
    {
        $jumlah = 0;
        $data   = json_decode($keranjang);
        if ((array)$data) {
            foreach ($data as $item) {
                $subtotal   = $item->jumlah * $item->harga_jual;
                $jumlah     = $jumlah + $subtotal;
            }
        }
        return $jumlah;
    }
}
if (! function_exists('totalhargadistribusi')) {
    function totalhargadistribusi($barang)
    {
        $jumlah = 0;
        $data   = json_decode($barang);
        if ((array)$data) {
            foreach ($data as $item) {
                $subtotal   = $item->jumlah * $item->harga_beli;
                $jumlah     = $jumlah + $subtotal;
            }
        }
        return $jumlah;
    }
}
if (! function_exists('subtotal')) {
    function subtotal($hargajual,$jumlah)
    {
        $subtotal = $hargajual * $jumlah;
        return $subtotal;
    }
}
if (! function_exists('jumlahretur')) {
    function jumlahretur($barang,$kodebarang)
    {
        $jumlah     = 0;
        if (!is_null($barang)) {
            $barang     = json_decode($barang);
            if (isset($barang->$kodebarang)) {
                $jumlah = $barang->$kodebarang->jumlah;
            }
        }
        return $jumlah;
    }
}
if (! function_exists('datajumlahretur')) {
    function datajumlahretur($jumlahretur,$jumlah)
    {
        $result = $jumlahretur;
        if ($jumlahretur == 0) {
            $result = $jumlah;
        }
        return $result;
    }
}
if (! function_exists('datainvoice')) {
    function datainvoice($keranjang,$uangpembeli)
    {
        $total  = 0;
        $bruto  = 0;
        $netto  = 0;
        foreach (json_decode($keranjang) as $item) {
            $subtotal = $item->harga_jual * $item->jumlah;
            $total  = $total + $subtotal;
            $bruto  = $bruto + ($item->harga_jual * $item->jumlah);
            $netto  = $netto + ($item->harga_beli * $item->jumlah);
        }
        $kembalian  = $uangpembeli - $total;
        $laba       = $bruto - $netto;
        $result     = [
            'total_pembayaran' => $total,
            'uang_pembeli' => $uangpembeli,
            'kembalian' => $kembalian,
            'bruto' => $bruto,
            'netto' => $netto,
            'laba' => $laba,
        ];
        return $result;
    }
}
if (! function_exists('statustransaksi')) {
    function statustransaksi($status)
    {
       switch ($status) {
           case 'selesai':
               $html = "<span class='badge badge-success'>SELESAI</span>";
               break;
           case 'proses':
               $html = "<span class='badge badge-warning'>PROSES</span>";
               break;
           case 'retur':
               $html = "<span class='badge badge-dark'>RETUR</span>";
               break;
           
           default:
               $html = NULL;
               break;
       }
       return $html;
    }
}
if (! function_exists('disablenominal')) {
    function disablenominal($total,$nominal)
    {
        $result   = NULL;
        if ($total > $nominal) {
            $result = 'disabled';
        }
        return $result;
    }
}
if (! function_exists('sortByOrder')) {
    function sortByOrder($a,$b)
    {
            return $b['jumlah'] - $a['jumlah'];
    }
}
if (! function_exists('sisauangeod')) {
    function sisauangeod($item)
    {
        $sisa   = $item->modal + $item->total_penjualan - $item->pengambilan;
        return $sisa;
    }
}
if (! function_exists('statistiklaporantransaksikategori')) {
    function statistiklaporantransaksikategori($transaksi)
    {
        $terjual  = 0;
        $penjualan  = 0;
        $hpp  = 0;
        $laba  = 0;
        foreach ($transaksi as $item) {
            foreach ($item as $key) {
                  // total omzet
                  $penjualan  = $penjualan + ($key['harga_jual'] * $key['jumlah']);
                  // total omzet
                  $hpp  = $hpp + ($key['harga_beli'] * $key['jumlah']);
                  // total item terjual
                  $terjual = $terjual + $key['jumlah'];
                  // total laba
                  $laba   = $laba + (($key['harga_jual'] - $key['harga_beli']) * $key['jumlah']);
            }
        }
    $statistik = [
        'terjual' => $terjual,
        'penjualan' => $penjualan,
        'hpp' => $hpp,
        'laba' => $laba,
    ];
    return $statistik;
    }
}

if (! function_exists('space')) {
    function space($sesi,$jumlah,$harga=null)
    {
        $ukuran     = 32 - 2; // Rp 
        $space      = NULL;
        switch ($sesi) {
            case 'harga':
                $batas      = $ukuran - 5; 
                $total      = $jumlah * $harga;
                $djumlah    = strlen($jumlah);
                $dharga     = strlen(norupiah($harga));
                $dtotal     = strlen(norupiah($total));
                $djarak     = 10 - $dtotal;
                $dsisa      = $batas - $djumlah - $dharga - $dtotal - $djarak;
            break;
            case 'total':
                $djumlah    = strlen(norupiah($jumlah));
                $dkata      = 5; // TOTAL
                $djarak     = 10 - $djumlah;
                $dsisa      = $ukuran - $djumlah - $dkata - $djarak;
            break;
            case 'bayar':
                $djumlah    = strlen(norupiah($jumlah));
                $dkata      = 5; // BAYAR
                $djarak     = 10 - $djumlah;
                $dsisa      = $ukuran - $djumlah - $dkata - $djarak;
            break;
            case 'kembalian':
                $djumlah    = strlen(norupiah($jumlah));
                $dkata      = 9; // KEMBALIAN
                $djarak     = 10 - $djumlah;
                $dsisa      = $ukuran - $djumlah - $dkata - $djarak;
                break;
            default:
                
                break;
        }
        for ($i=0; $i < $dsisa; $i++) { 
            $space .= ' ';
        }
        $space .= 'Rp';
        for ($i=0; $i < $djarak; $i++) { 
            $space .= ' ';
        }
        return $space;
    }
}

if (! function_exists('detailtransaksikategori')) {
    function detailtransaksikategori($item)
    {
        $terjual    = 0;
        $harga      = [];
        $penjualan  = 0;
        $hpp        = 0;
        $laba       = 0;
        foreach ($item as $k) {
            if (!in_array($k['harga_jual'],$harga)) {
                $harga[] = $k['harga_jual'];
            }
            $terjual        = $terjual + $k['jumlah'];
            $npenjualan     = ($k['jumlah'] * $k['harga_jual']);
            $penjualan      = $penjualan + $npenjualan;
            $nhpp           = $k['jumlah'] * $k['harga_beli'];
            $hpp            = $hpp + $nhpp;
            $laba           = $laba + ($npenjualan - $nhpp);
        }
        $detail     = [
            'terjual' => $terjual,
            'harga' => $harga,
            'penjualan' => $penjualan,
            'hpp' => $hpp,
            'laba' => $laba
        ];
        return $detail;
    }
}

// DISTRIBUSI
if (! function_exists('totalpembayarandistribusi')) {
    function totalpembayarandistribusi($data)
    {
        $total  = 0;
        foreach ($data as $item) {
            $barang     = json_decode($item->barang);
            $subtotal   = 0;
            foreach ($barang as $key) {
                $subtotal = $subtotal + ($key->harga_beli * $key->jumlah);
            }
            // dikurang potongan
            $potongan   = (integer) $item->potongan;
            $total = $total + ($subtotal - $potongan);
        }
        return $total;
    }
}




