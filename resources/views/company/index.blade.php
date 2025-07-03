@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 font-semibold text-lg">Daftar Perusahaan</h2>

    <a href="{{ route('company.create') }}" class="btn btn-primary mb-3">+ Tambah Perusahaan</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h5 class="font-semibold">Data Aktif</h5>
    <table id="dataAktif" class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Domisili</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Blacklist</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $c)
            <tr>
                <td>{{ $c->nama }}</td>
                <td>{{ $c->domisili->nama ?? '-' }}</td>
                <td>{{ $c->telepon }}</td>
                <td>{{ $c->email }}</td>
                <td>
                    @if($c->blacklist)
                        <span class="badge bg-danger">Yes</span><br>
                        <small>{{ $c->alasan_blacklist }}</small><br>
                        <form action="{{ route('company.unblacklist', $c->id) }}" method="POST">@csrf
                            <button class="btn btn-sm btn-warning mt-1">Batalkan</button>
                        </form>
                    @else
                        <form action="{{ route('company.blacklist', $c->id) }}" method="POST">@csrf
                            <input type="text" name="alasan_blacklist" class="form-control form-control-sm" placeholder="Alasan" required>
                            <button class="btn btn-sm btn-danger mt-1">Blacklist</button>
                        </form>
                    @endif
                </td>
                <td>
                    <a href="{{ route('company.edit', $c->id) }}" class="btn btn-sm btn-info">Edit</a>
                    <form action="{{ route('company.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus data?')" style="display:inline-block">@csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-4">

    <h5 class="font-semibold">Data Terhapus</h5>
    <table id="dataTerhapus" class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Email</th>
                <th>Restore</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deletedCompanies as $c)
            <tr>
                <td>{{ $c->nama }}</td>
                <td>{{ $c->telepon }}</td>
                <td>{{ $c->email }}</td>
                <td>
                    <form action="{{ route('company.restore', $c->id) }}" method="POST">@csrf
                        <button class="btn btn-sm btn-success">Restore</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    new simpleDatatables.DataTable("#dataAktif");
    new simpleDatatables.DataTable("#dataTerhapus");
</script>
@endsection
