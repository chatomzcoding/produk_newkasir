<div class="col-md-2">
    <select name="tahun" id="" class="form-control" onchange="this.form.submit()">
        @for ($i = 2020; $i <= ambil_tahun(); $i++)
            <option value="{{ $i }}" @if ($filter['data']['tahun'] == $i)
                selected
            @endif>{{ $i }}</option>
        @endfor
    </select>
</div>