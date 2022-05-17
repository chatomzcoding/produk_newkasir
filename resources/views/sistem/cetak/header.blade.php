<header>
    <table class="table" style=" border: 0px solid #fff">
        <tr>
            <td width="20%" style=" border: 0px solid #fff">
                <img src="{{ asset('img/client/'.$main['client']->logo) }}" width="90px" alt="logo">
            </td>
            <td class="text-center" style=" border: 0px solid #fff">
                <h2>{{ strtoupper($main['client']->nama_toko) }}</h2> 
                <small style="font-family: calibri;">Telepon : {{ $main['client']->no_telp}}</small> <br>
                <small style="font-family: calibri;">{{ $main['client']->alamat}}</small>
            </td>
        </tr>
     </table>
     <hr> 
 </header>