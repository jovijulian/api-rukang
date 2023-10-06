@extends('layouts/content')

@section('title')
  <title>Verifikasi User</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>User</h4>
        <h6>Verifikasi User</h6>
      </div>
      {{-- <div class="page-btn">
        <a href="addproduct.html" class="btn btn-added"><img src="{{ url('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Add
          New Product</a>
      </div> --}}
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            {{-- <div class="search-path">
              <a class="btn btn-filter" id="filter_search">
                <img src="{{ url('assets/img/icons/filter.svg') }}" alt="img">
                <span><img src="{{ url('assets/img/icons/closes.svg') }}" alt="img"></span>
              </a>
            </div> --}}
            <div class="search-input">
              <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                    src="{{ url('assets/img/icons/pdf.svg') }}" alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                    src="{{ url('assets/img/icons/excel.svg') }}" alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                    src="{{ url('assets/img/icons/printer.svg') }}" alt="img"></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive">
          <table id="data-user-inactive" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Tanggal Lahir</th>
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
        Swal.fire('User Berhasil Terverifikasi!', '', 'success')
        sessionStorage.removeItem("success")
      }

      // REDIRECT IF NOT ADMIN
      if(!currentUser.isAdmin) {
        window.location.href = "{{ url('/dashboard') }}"
      }

      // GET DATA
      const table = $('#data-user-inactive')

      getData()

      // GET USER NOT VERIFY
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
            url: "{{ url('api/v1/user/datatable-inactive') }}",
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
            {data: 'fullname'},
            {data: 'email'},
            {data: 'phone_number'},
            {data: 'address'},
            {data: 'birthdate'},
            {
              data: 'created_at',
              render: function (data) {
                return new Date(data).toISOString().split('T')[0].split('-').reverse().join('-')
              }
            },
            {
              data: 'updated_at',
              render: function (data) {
                return new Date(data).toISOString().split('T')[0].split('-').reverse().join('-')
              }
            },
            {data: 'created_by'},
            {data: 'updated_by'},
            {
              data: 'id', 
              orderable: false,
              searchable: false,
              render: function(data) {
                return `<button onclick="updateUserActive('${data}')" class="p-2 btn btn-submit" >Verifikasi Akun</button>`
              }
            },
          ]
        })
      }
    })

    function updateUserActive(id) {
      Swal.fire({
        title: 'Yakin ingin memverifikasi user?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#global-loader').show()

          axios.put(`{{ url('api/v1/user/update-status-user/${id}') }}`, {
              isActive: 1
            }, config)
            .then(res => {
              sessionStorage.setItem("success", "User Berhasil Terverifikasi!")
              location.reload()
            })
            .catch(err => {
              Swal.fire('User Gagal Terverifikasi!', '', 'error')
              console.log(err)
            })
        }
      })
    }
  </script>
@endsection
