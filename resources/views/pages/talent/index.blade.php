@extends('layouts/content')

@section('title')
    <title>Talent</title>
@endsection

@section('content')
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Talent</h4>
                <h6>Manajemen Data Talent</h6>
            </div>
            <div class="page-btn">
                <a href="/talent/insert" class="btn btn-added remove-role"><img src="{{ url('assets/img/icons/plus.svg') }}"
                        alt="img" class="me-1">Tambah Talent
                    Baru</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}"
                                    alt="img"></a>
                        </div>
                    </div>
                    <div class="wordset">
                        <ul>
                            <li>
                                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                        src="{{ url('assets/img/icons/excel.svg') }}" alt="img"></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="table-responsive pb-4">
                    <table id="shelf-table" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Talent</th>
                                <th>Nomor Handphone</th>
                                <th>Rating</th>
                                <th>Kategori Servis 1</th>
                                <th>Kategori Servis 2</th>
                                <th>Kategori Servis 3</th>
                                <th>Kategori Servis 4</th>
                                <th>Dibuat Pada</th>
                                <th>Diubah Pada</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="detailPhoto" tabindex="-1" aria-labelledby="detailPhotoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-3">
                    <div class="modal-header flex justify-content-between">
                        <h5 class="modal-title" id="detailPhotoLabel">Detail Foto</h5>
                        <div class="flex">
                            <a id="update-all" class="p-2 btn btn-primary p-2 text-white me-2">Ubah Semua</a>
                            <button id="delete-all-image" class="p-1 btn btn-danger p-2 text-white">Hapus Semua</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div id="row-photo" class="row justify-content-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const tokenType = localStorage.getItem('token_type')
        const accessToken = localStorage.getItem('access_token')

        let hiddenRole = false



        hiddenRole && $('.remove-role').remove()

        let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        let config = {
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `${tokenType} ${accessToken}`
            }
        }

        let dataProgress = []

        $(document).ready(function() {
            // NOTIF VERIFY USER
            const success = sessionStorage.getItem("success")
            if (success) {
                Swal.fire(success, '', 'success')
                sessionStorage.removeItem("success")
            }

            // REDIRECT IF NOT ADMIN
            // if(!currentUser.isAdmin) {
            //   window.location.href = "{{ url('/dashboard') }}"
            // }

            // GET DATA
            const table = $('#shelf-table')

            getData()

            // GET SHELF
            function getData() {
                table.DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    bInfo: true,
                    sDom: 'frBtlpi',
                    ordering: true,
                    pagingType: 'numbers',
                    language: {
                        search: ' ',
                        sLengthMenu: '_MENU_',
                        searchPlaceholder: "Search...",
                        info: "_START_ - _END_ of _TOTAL_ items",
                    },
                    initComplete: (settings, json) => {
                        dataProgress = json.data
                        $('.dataTables_filter').appendTo('.search-input')
                    },
                    ajax: {
                        url: "{{ url('api/v1/talent/datatable') }}",
                        dataType: 'json',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Authorization': `${tokenType} ${accessToken}`
                        },
                        error: function(err) {
                            console.log(err)
                        }
                    },
                    columns: [{
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            },
                        },
                        {
                            data: 'fullname'
                        },

                        {
                            data: 'phone_number'
                        },
                        {
                            data: 'rating'
                        },
                        {
                            data: 'category_name1'
                        },
                        {
                            data: 'category_name2'
                        },
                        {
                            data: 'category_name3'
                        },
                        {
                            data: 'category_name4'
                        },
                        {
                            data: 'created_at',
                            render: function(data) {
                                return new Date(data).toISOString().split('T')[0].split('-')
                                    .reverse().join('-')
                            }
                        },
                        {
                            data: 'updated_at',
                            render: function(data) {
                                return data ? new Date(data).toISOString().split('T')[0].split('-')
                                    .reverse().join('-') : ''
                            }
                        },

                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function(data) {
                                return `
                   <a class="me-3" onclick="deleteData('` + data + `')">
                      <img src="assets/img/icons/delete.svg" alt="img">
                    </a>

                  `
                            }
                        },
                    ]
                })

            }
        })



        function deleteData(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus talent?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#global-loader').show()

                    axios.delete(`{{ url('api/v1/talent/delete/${id}') }}`, config)
                        .then(res => {
                            sessionStorage.setItem("success", "Talent berhasil dihapus")
                            location.reload()
                        })
                        .catch(err => {
                            $('#global-loader').hide()
                            Swal.fire('Talent gagal dihapus!', '', 'error')
                            console.log(err)
                        })
                }
            })
        }
    </script>
@endsection
