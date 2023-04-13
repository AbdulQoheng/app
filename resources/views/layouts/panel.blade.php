<!DOCTYPE html>
<!--
Template Name: NobleUI - Admin & Dashboard Template
Author: NobleUI
Website: https://www.nobleui.com
Contact: nobleui123@gmail.com
Purchase: https://1.envato.market/nobleui_admin
License: You must have a valid license purchased only from above link or https://themeforest.net/user/nobleui/portfolio/ in order to legally use the theme for your project.
-->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cv Singergi Teknokarya</title>
    <!-- core:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/core/core.css') }}">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- endinject -->
    @stack('css')
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo_1/style.css') }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>

<body>
    <div class="main-wrapper">

        @include('layouts.sidebar')

        <div class="page-wrapper">

            @include('layouts.navbar')

            @yield('page-content')

            <!-- Modal -->
            <div id="modal-data-change-password" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
                aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Ubah Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form class="forms-sample">

                                <div class="form-group">
                                    <label for="change-password">Password</label>
                                    <input type="text" class="form-control" id="change-password" autocomplete="off"
                                        placeholder="Password">
                                    <div id="change-passwordFeedback" class="invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="konfirmasi-password">Konfirmasi Password</label>
                                    <input type="text" class="form-control" id="konfirmasi-password" autocomplete="off"
                                        placeholder="Konfirmasi Password">
                                    <div id="konfirmasi-passwordFeedback" class="invalid-feedback"></div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button id="btnSavePassword" type="button" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.footer')

        </div>
    </div>

    <!-- core:js -->
    <script src="{{ asset('assets/vendors/core/core.js') }}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    @stack('js')
    <!-- end plugin js for this page -->
    <!-- inject:js -->
    <script src="{{ asset('assets/vendors/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/vendors/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- endinject -->

    @stack('customjs')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });
        function changePassword() {
            $('#modal-data-change-password').modal('show');
            $('#change-password').val('')
            $('#konfirmasi-password').val('')
        }

        $(document).ready(function() {
            $('body').on('click', '#btnSavePassword', function() {
                if ($('#change-password').val() == $('#konfirmasi-password').val()) {
                    $.ajax({
                    data: {

                        password: $('#change-password').val(),
                    },
                    url: "{{ route('password.change') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        var notif = notifikasi_toas('success', 'Berhasil Menyimpan data');
                        $('#btnSavePassword').html('Simpan');
                        $('#modal-data-change-password').modal('hide');

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
                        $('#btnSave').html('Simpan');

                    }
                });
                }else{
                    var notif = notifikasi_toas('error', 'Konfirmasi Password Salah');
                }

            });
        });

    </script>
</body>

</html>
