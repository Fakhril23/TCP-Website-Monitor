@extends('layouts.app')

@section('content')

<h2>Tambah Website</h2>

<form method="POST" action="{{ route('websites.store') }}">
    @csrf

    <table border="1" cellpadding="8" id="websiteTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Website</th>
                <th>URL</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td>1</td>

                <td>
                    <input type="text" name="name[]" required>
                </td>

                <td>
                    <input type="text" name="url[]" required>
                </td>

                <td>
                    <button type="button" onclick="hapusBaris(this)">
                        HAPUS
                    </button>
                </td>
            </tr>

        </tbody>
    </table>

    <br>

    <button type="button" onclick="tambahBaris()">
        + TAMBAH WEBSITE
    </button>

    <br><br>

    <button type="submit">
        SIMPAN SEMUA
    </button>

    <a href="{{ route('websites.index') }}">
        <button type="button">BATAL</button>
    </a>

</form>


<script>

function tambahBaris() {

    let table = document.querySelector("#websiteTable tbody");

    let jumlahBaris = table.rows.length + 1;

    let row = table.insertRow();

    row.innerHTML = `
        <td>${jumlahBaris}</td>

        <td>
            <input type="text" name="name[]" required>
        </td>

        <td>
            <input type="text" name="url[]" required>
        </td>

        <td>
            <button type="button" onclick="hapusBaris(this)">
                HAPUS
            </button>
        </td>
    `;
}


function hapusBaris(button) {

    let row = button.closest("tr");

    row.remove();

    updateNomor();
}


function updateNomor() {

    let rows = document.querySelectorAll("#websiteTable tbody tr");

    rows.forEach((row, index) => {

        row.cells[0].innerText = index + 1;

    });
}

</script>

@endsection
