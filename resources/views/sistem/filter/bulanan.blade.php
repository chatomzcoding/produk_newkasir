<div class="col-md-2">
    <select name="bulan" id="" class="form-control" onchange="this.form.submit()">
        @for ($i = 1; $i <= 12; $i++)
            <option value="{{ $i }}" @if ($filter['data']['bulan'] == $i)
                selected
            @endif>{{ bulan_indo($i) }}</option>
        @endfor
    </select>
</div>
<div class="col-md-2">
    <select name="tahun" id="" class="form-control" onchange="this.form.submit()">
        @for ($i = 2020; $i <= ambil_tahun(); $i++)
            <option value="{{ $i }}" @if ($filter['data']['tahun'] == $i)
                selected
            @endif>{{ $i }}</option>
        @endfor
    </select>
</div>