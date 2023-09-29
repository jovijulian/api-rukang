@extends('layouts/content')

@section('title')
  <title>Produk</title>
@endsection


@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Produk</h4>
        <h6>Manajemen data produk</h6>
      </div>
      <div class="page-btn">
        <a href="/product/insert" class="btn btn-added remove-role"><img src="assets/img/icons/plus.svg" alt="img" class="me-1">Tambah produk baru</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="table-top">
          <div class="search-set">
            <div class="search-input">
              <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a onclick="toExcel()" data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img"></a>
              </li>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="assets/img/icons/printer.svg" alt="img"></a>
              </li>
            </ul>
          </div>
        </div>

        <div class="table-responsive pb-4">
          <table id="product-table" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Action</th>
                <th>Kategori</th>
                <th>Segmen</th>
                <th>Nomor Modul</th>
                <th>Nomor Bilah</th>
                <th>Nomor Rak</th>
                <th>Tanggal Produksi</th>
                <th>Keterangan</th>
                <th>Tanggal Pengiriman</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Dibuat Oleh</th>
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
    
    if (currentUser.isAdmin == 4) {
      hiddenRole = true
    } else if (currentUser.isAdmin == 5) {
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
      // if (!currentUser.isAdmin) {
      //   window.location.href = "{{ url('/dashboard') }}"
      // }

      // GET DATA
      const table = $('#product-table')


      getData()

      // GET PRODUCT
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
            url: "{{ url('api/v1/product/datatable') }}",
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
            {
              data: 'id',
              orderable: false,
              searchable: false,
              render: function(data) {
                if (currentUser.isAdmin == 1 || currentUser.isAdmin == 2) {
                  return `
                    <div class="ms-2 mb-2">
                      <a class="me-3" href="product/detail/` + data + ` ">
                        <img src="assets/img/icons/eye.svg" alt="img">
                      </a>
                      <a class="me-3" href="/product/edit/` + data + `">
                        <img src="assets/img/icons/edit.svg" alt="img">
                      </a>
                      <a class="me-3" onclick="deleteData('` + data + `')">
                        <img src="assets/img/icons/delete.svg" alt="img">
                      </a>
                    </div>
                    <a class="btn btn-submit text-white p-1" href="product/update-status/` + data + `">Update Status</a>

                  `                  
                } else {
                  return `
                    <div class="ms-2 mb-2">
                      <a class="me-3" href="product/detail/` + data + ` ">
                        <img src="assets/img/icons/eye.svg" alt="img">
                      </a>
                      <a class="me-3" href="/product/edit/` + data + `" ${hiddenRole && 'hidden'}>
                        <img src="assets/img/icons/edit.svg" alt="img">
                      </a>
                    </div>
                    <a class="btn btn-submit text-white p-1" href="product/update-status/` + data + `" ${currentUser.isAdmin == 5 && 'hidden'}>Update Status</a>
                  `
                }
              }
            },
            {data: 'category'},
            {data: 'segment_name'},
            {data: 'module_number'},
            {data: 'bilah_number'},
            {data: 'shelf_name'},
            {
              data: 'production_date',
              render: function (data) {
                return data ? new Date(data).toISOString().split('T')[0].split('-').reverse().join('-') : ''
              }
            },
            {data: 'description'},
            {
              data: 'delivery_date',
              render: function (data) {
                return new Date(data).toISOString().split('T')[0].split('-').reverse().join('-')
              }
            },
            {data: 'status'},
            {
              data: 'created_at',
              render: function (data) {
                return new Date(data).toISOString().split('T')[0].split('-').reverse().join('-')
              }
            },
            {data: 'created_by'},
          ],
        })
      }
    })

    function deleteData(id) {
      Swal.fire({
        title: 'Yakin ingin menghapus produk?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#global-loader').show()

          axios.delete(`{{ url('api/v1/product/delete/${id}') }}`, config)
            .then(res => {
              sessionStorage.setItem("success", "Produk berhasil dihapus")
              location.reload()
            })
            .catch(err => {
              $('#global-loader').hide()
              Swal.fire('Produk gagal dihapus!', '', 'error')
              console.log(err)
            })
        }
      })
    }

    function toExcel() {
      Swal.fire({
        // title: 'Pilih Segmen',
        // icon: 'question',
        padding: '3em',
        html:
          `
            <select id="segment-product" class="form-control select text-start">
              <option value="pilih segmen" selected="selected" disabled>Pilih segmen</option>
            </select>
            <a href="{{ url('api/v1/product/report-product') }}" id="export-excel" class="w-100 mt-3 btn btn btn-submit">Eksport Segmen</a>
          `,
        // showCancelButton: true,
        showConfirmButton: false,
      })

      getSegment()

      function getSegment() {
        $('#segment-product').select2({
          ajax: {
            url: "{{ url('api/v1/segment/index') }}",
            headers: config.headers,
            dataType: 'json',
            type: "GET",
            data: function(params) {
              var query = {
                search: params.term,
                page: params.page || 1
              }
              return query
            },
            processResults: function(data, params) {
              params.page = params.page || 1
              segments = data.data.items

              $('#mySelect2').append(new Option('Semua Data', 0, false, false))


              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.segment_name,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true,
          }
        })

        $('#segment-product').on('change', () => {
          const id = $('#segment-product').val()
          const url = `{{ url('api/v1/product/report-product') }}?segment=${id}`
          $('#export-excel').attr('href', url);
        })
      }
    }

  </script>
@endsection
