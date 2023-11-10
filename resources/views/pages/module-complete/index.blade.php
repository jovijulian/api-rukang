@extends('layouts/content')

@section('title')
  <title>Kelengkapan Modul</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Kelengkapan Modul</h4>
        <h6>Manajemen Data Kelengkapan Modul</h6>
      </div>
      <div class="page-btn">
        <a href="/module-complete/insert" class="btn btn-added remove-role"><img src="{{ url('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Kelengkapan Modul Baru</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            <div class="search-input">
              <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="{{ url('assets/img/icons/excel.svg') }}" alt="img"></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive pb-4">
          <table id="shelf-table" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Segmen</th>
                <th>Nama Modul</th>
                <th>Lengkap</th>
                <th>Dibuat Pada</th>
                <th>Diubah Pada</th>
                <th>Dibuat Oleh</th>
                <th>Diubah Oleh</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    const currentUser = JSON.parse(localStorage.getItem('current_user'))
    const tokenType = localStorage.getItem('token_type')
    const accessToken = localStorage.getItem('access_token')

    let hiddenRole = false
    
    if (currentUser.isAdmin == 5) {
      hiddenRole = true
    }

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
          initComplete: (settings, json)=>{
            $('.dataTables_filter').appendTo('.search-input')
          },
          ajax: {
            url: "{{ url('api/v1/module-completeness/datatable') }}",
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
          columns: [
            {
              data: 'id',
              orderable: false,
              searchable: false,
              render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
              },
            },
            {data: 'segment'},
            {data: 'module'},
            {
              data: 'completeness',
              render: function (data) {
                return data ? '<span class="badges bg-lightgreen">Ya</span>' : '<span class="badges bg-lightred">Tidak</span>'
              }
            },
            {
              data: 'created_at',
              render: function (data) {
                return new Date(data).toISOString().split('T')[0].split('-').reverse().join('-')
              }
            },
            {
              data: 'updated_at',
              render: function (data) {
                return data ? new Date(data).toISOString().split('T')[0].split('-').reverse().join('-') : ''
              }
            },
            {data: 'created_by'},
            {data: 'updated_by'},
            {
              data: 'id', 
              orderable: false,
              searchable: false,
              render: function(data) {
                if (currentUser.isAdmin == 1 || currentUser.isAdmin == 2) {
                  return `
                    <a class="me-3" href="/module-complete/edit/` + data + `">
                      <img src="assets/img/icons/edit.svg" alt="img">
                    </a>
                  `
                } else {
                  return `
                    <a class="me-3" href="/module-complete/edit/` + data + `"  ${hiddenRole && 'hidden'}>
                      <img src="assets/img/icons/edit.svg" alt="img">
                    </a>
                  `
                }
              }
            },
          ]
        })

      }
    })

    function deleteData(id) {
      Swal.fire({
        title: 'Yakin ingin menghapus rak?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#global-loader').show()

          axios.delete(`{{ url('api/v1/shelf/delete/${id}') }}`, config)
            .then(res => {
              sessionStorage.setItem("success", "Rak berhasil dihapus")
              location.reload()
            })
            .catch(err => {
              Swal.fire('Rak gagal dihapus!', '', 'error')
              console.log(err)
            })
        }
      })
    }
  </script>
@endsection