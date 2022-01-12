<header>
    <table class="table" style=" border: 0px solid #fff">
        <tr>
            <td width="20%" style=" border: 0px solid #fff">
                <img src="{{ asset('img/client/'.$main['client']->logo) }}" width="90px" alt="logo">
            </td>
            <td class="text-center" style=" border: 0px solid #fff">
                <h2>{{ $main['client']->nama_toko }}</h2> 
                <small style="font-family: calibri;">Telepon : {{ $main['telp']}}</small> <br>
                <small style="font-family: calibri;">{{ $main['alamat']}}</small>
            </td>
        </tr>
     </table>
     <hr> 
 </header>