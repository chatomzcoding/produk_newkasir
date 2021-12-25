@section('head')
<style>
    input[type=text] {
        border: 2px solid #bdbdbd;
        font-family: 'Roboto', Arial, Sans-serif;
        font-size: 15px;
        font-weight: 400;
        padding: .5em .75em;
        width: 100%;
    }
    input[type=text]:focus {
        border: 2px solid #757575;
        outline: none;
    }
    .autocomplete-suggestions {
        border: 1px solid #999;
        background: #FFF;
        overflow: auto;
    }
    .autocomplete-suggestion {
        padding: 2px 5px;
        white-space: nowrap;
        overflow: hidden;
    }
    .autocomplete-selected {
        background: #F0F0F0;
    }
    .autocomplete-suggestions strong {
        font-weight: normal;
        color: #3399FF;
    }
    .autocomplete-group {
        padding: 2px 5px;
    }
    .autocomplete-group strong {
        display: block;
        border-bottom: 1px solid #000;
    }
</style>
@endsection
<section>
    <h2>Cari Barang</h2>
    <form action="{{ url('transaksi') }}" method="post">
        @csrf
        <input type="hidden" name="sesi" value="tambahbarangpencarian">
        <input type="hidden" name="transaksi_id" value="{{ $transaksi->id }}">
        <div class="form-group">
            <input type="text" id="nama" name="nama_barang" class="form-control" placeholder="Nama Barang" value=""  @if ($s == 'caribarang')
            autofocus
        @endif autofocus>
        </div>
    </form>
</section>