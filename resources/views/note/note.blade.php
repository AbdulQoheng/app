@extends('layouts.panel')

@push('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endpush

@section('page-content')
    <div class="page-content">
        <nav class="page-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="color-primary">Note</a></li>
                <li class="breadcrumb-item active" aria-current="page">Note</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
            <div></div>
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <button id="btnAdd" type="button" class="btn btn-primary btn-icon-text mb-2 mb-md-0">
                    <i class="btn-icon-prepend" data-feather="file-plus"></i>
                    Tambah Data
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Table Note</h6>
                        <div class="table-responsive">
                            <table id="table-data" class="table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Note</th>
                                        <th>User</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal-data" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-titel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="forms-sample">
                        <input type="hidden" id="id">
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea class="form-control" id="note" rows="5"></textarea>
                            <div id="noteFeedback" class="invalid-feedback"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button id="btnSave" type="button" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush
@push('customjs')
    <script>
        var bSave = true;
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
        var table = $('#table-data').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('note') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'note',
                    name: 'note'
                },
                {
                    data: 'user.name',
                    name: 'user.name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        $(document).ready(function() {
            $('body').on('click', '#btnAdd', function() {
                $('#modal-titel').html('Tambah Data')

                $('#note').removeClass('is-invalid');
                $('#noteFeedback').html('');

                $('#id').val('')
                $('#note').val('')

                $('#btnSave').html('Simpan')
                $('#modal-data').modal('show');
            });
        });
        $(document).ready(function() {
            $('body').on('click', '#btnEdit', function() {
                ids = $(this).attr("data-id");
                var url = "{{ route('note.show', ['note' => ':id']) }}";
                url = url.replace(':id', ids);
                $.get(url, function(response) {
                    $('#modal-titel').html('Ubah Data')

                    $('#note').removeClass('is-invalid');
                    $('#noteFeedback').html('');

                    $('#id').val(response.id_note)

                    $('#note').val(response.note)


                    $('#btnSave').html('Simpan')
                    $('#modal-data').modal('show');
                })

            });
        });
        $(document).ready(function() {
            $('body').on('click', '#btnDelete', function() {
                var dataId = $(this).attr("data-id");
                var data = dataId.split('#');

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger mr-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Apa kamu Yakin?',
                    text: "Banner " + data[1] + " Diapus",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonClass: 'mr-2',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Tidak, Batalkan!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        var url = "{{ route('note.delete', ['note' => ':id']) }}"
                        url = url.replace(':id', data[0]);
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: 'json',
                            success: function(data) {
                                table.draw();
                                swalWithBootstrapButtons.fire(
                                    'Hapus!',
                                    'Data Berhasil Dihapus.',
                                    'success'
                                )
                            },
                            error: function(data) {
                                table.draw();
                                swalWithBootstrapButtons.fire(
                                    'Gagal',
                                    'Terjadi kesalahan saat menghapus',
                                    'error'
                                )
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        swalWithBootstrapButtons.fire(
                            'Batal',
                            'Data Tidak Diahapus :)',
                            'error'
                        )
                    }
                })
            });
        });
        $(document).ready(function() {
            $('body').on('click', '#btnSave', function() {
                if (bSave) {
                    bSave = false;
                    $('#btnSave').html('Proses...');
                    $.ajax({
                        data: {
                            id: $('#id').val(),
                            note: $('#note').val(),
                        },
                        url: "{{ route('note.save') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function(data) {
                            table.draw();
                            var notif = notifikasi_toas('success', 'Berhasil Menyimpan data');
                            $('#btnSave').html('Simpan');
                            bSave = true;
                            $('#modal-data').modal('hide');
                        },
                        error: function(data) {
                            var respon = data.responseJSON;
                            var errors = respon.errors;
                            var notif = notifikasi_toas('error', 'Gagal Menyimpan data');
                            for (const key in errors) {
                                const tag = document.getElementById(
                                    key + "Feedback");
                                document.getElementById(key).classList.add("is-invalid")
                                tag.innerHTML = '';
                                errors[key].forEach(pesan => {

                                    tag.innerHTML += `${pesan}<br>`;
                                });

                            }
                            bSave = true;
                            $('#btnSave').html('Simpan');

                        }
                    });
                }

            });
        });
    </script>
@endpush
