<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Pembeli</label>
                    <select name="user_id" id="user_id" class="form-control" required>
                        <option value="">- Pilih Pembeli -</option>
                        @foreach($user as $u)
                            <option value="{{ $u->user_id }}">{{ $u->username }}</option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Penjualan Kode</label>
                    <input value="" type="text" name="penjualan_kode" id="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input value="" type="date" name="penjualan_tanggal" id="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>

                <!-- Penjualan Detail -->
                <table class="table" id="penjualan-detail">
                    <thead>
                        <tr>
                            <th>Stok</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="stok_id[]" class="form-control" required>
                                    <option value="">- Pilih Stok -</option>
                                    @foreach($stok as $s)
                                        <option value="{{ $s->stok_id }}">{{ $s->barang->barang_nama }} (Stok: {{ $s->stok_jumlah }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control" required min="1">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" id="add-row" class="btn btn-success btn-sm">Tambah Detail</button>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function () {
        // Fungsi untuk menambahkan baris baru
        $("#add-row").click(function () {
            const newRow = `
                <tr>
                    <td>
                        <select name="stok_id[]" class="form-control" required>
                            <option value="">- Pilih Stok -</option>
                            @foreach($stok as $s)
                                <option value="{{ $s->stok_id }}">{{ $s->barang->barang_nama }} (Stok: {{ $s->stok_jumlah }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control" required min="1">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                    </td>
                </tr>
            `;
            $("#penjualan-detail tbody").append(newRow);
        });

        // Fungsi untuk menghapus baris
        $("#penjualan-detail").on("click", ".remove-row", function () {
            $(this).closest("tr").remove();
        });

        // Validasi form
        $("#form-tambah").validate({
            rules: {
                "user_id": {
                    required: true
                },
                "penjualan_kode": {
                    required: true
                },
                "penjualan_tanggal": {
                    required: true
                },
                "stok_id[]": {
                    required: true
                },
                "jumlah[]": {
                    required: true,
                    min: 1
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataPenjualan.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    }); 
</script>