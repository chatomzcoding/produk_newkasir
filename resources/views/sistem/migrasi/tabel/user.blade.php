<table class="table table-bordered table-striped">
    <thead class="text-center">
        <tr>
            <th width="5%">No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Level</th>
        </tr>
    </thead>
    <tbody class="text-capitalize">
    @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->level }}</td>
        </tr>
    @endforeach
    </tbody>
</table>