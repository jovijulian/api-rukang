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
            <div class="search-path">
              <a class="btn btn-filter" id="filter_search">
                <img src="{{ url('assets/img/icons/filter.svg') }}" alt="img">
                <span><img src="{{ url('assets/img/icons/closes.svg') }}" alt="img"></span>
              </a>
            </div>
            <div class="search-input">
              <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img"></a>
            </div>
          </div>
          <div class="wordset">
            <ul>
              <li>
                <a onclick="toExcel()" data-toggle="modal" data-target="#modalExport" data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img"></a>
                {{-- <a onclick="toExcel()" data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img"></a> --}}
              </li>
            </ul>
          </div>
        </div>

        <div class="card mb-0" id="filter_inputs">
          <div class="card-body pb-0">
            <div class="row">
              <div class="col-lg-12 col-sm-12">
                <div class="row">
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select id="category-filter" class="form-control select filter-product">
                        {{-- <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option> --}}
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select id="segment-filter" class="form-control select filter-product">
                        {{-- <option value="pilih kategori" selected="selected" disabled>Pilih segmen</option> --}}
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select id="module-filter" class="form-control select filter-product">
                        {{-- <option value="pilih kategori" selected="selected" disabled>Pilih modul</option> --}}
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-1 col-sm-6 col-12">
                    <div class="form-group">
                      <a id="delete-filter-data" class="btn btn-filters bg-danger w-100"><img src="assets/img/icons/closes.svg" alt="img"></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
                <th>Barcode</th>
                <th>Tanggal Mulai Produksi</th>
                <th>Tanggal Selesai Produksi</th>
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

    <div class="modal fade p-3" id="modalExport" tabindex="-1" aria-labelledby="modalExportLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
          <div class="modal-header">
            <h5 class="modal-title" id="modalExportLabel">Export ke Excel</h5>
          </div>
          <div class="modal-body">
            <form id="export-form">
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Kategori *</label>
                <div class="col-lg-9">
                  <select id="category-export" class="form-control select" required>
                    {{-- <option selected="selected" disabled>Pilih kendaraan</option> --}}
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Segmen *</label>
                <div class="col-lg-9">
                  <select id="segment-export" class="form-control select" required>
                    {{-- <option selected="selected" disabled>Pilih kendaraan</option> --}}
                  </select>
                </div>
              </div>
              <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-3 py-2">Export</button>
              </div>
            </form>
          </div>
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
      // const table = $('#product-table')

      let categoryFilter = $('#category-filter').val()
      let segmentFilter = $('#segment-filter').val()
      let moduleFilter = $('#module-filter').val()



      // GET PRODUCT
      const table = $('#product-table').DataTable({
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
          data: function(d) {
            d.category = categoryFilter
            d.segment = segmentFilter
            d.module = moduleFilter
            return d
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
            render: function(data, type, row, meta) {
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
                  <div class="ms-2 mb-2 flex align-center">
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
                  <a class="btn btn-submit text-white p-1 flex align-center" style="width: 155px" href="product/add-status/` + data + `"><img src="assets/img/icons/plus1.svg" alt="img" class="me-1"> Tambah Status</a>
                `
              } else {
                return `
                  <div class="ms-2 mb-2 flex align-center">
                    <a class="me-3" href="product/detail/` + data + ` ">
                      <img src="assets/img/icons/eye.svg" alt="img">
                    </a>
                    <a class="me-3" href="/product/edit/` + data + `" ${hiddenRole && 'hidden'}>
                      <img src="assets/img/icons/edit.svg" alt="img">
                    </a>
                  </div>
                  <a class="btn btn-submit text-white p-1 flex align-center" style="width: 170px" href="product/add-status/` + data + `" ${currentUser.isAdmin == 5 && 'hidden'}><img src="assets/img/icons/plus1.svg" alt="img" class="me-1"> Tambah Status</a>
                `
              }
            }
          },
          {
            data: 'category',
          },
          {
            data: 'segment_name'
          },
          {
            data: 'module_number'
          },
          {
            data: 'bilah_number'
          },
          {
            data: 'shelf_name'
          },
          {
            data: 'barcode'
          },
          {
            data: 'start_production_date',
            render: function(data) {
              return data ? new Date(data).toISOString().split('T')[0].split('-').reverse().join('-') : ''
            }
          },
          {
            data: 'finish_production_date',
            render: function(data) {
              return data ? new Date(data).toISOString().split('T')[0].split('-').reverse().join('-') : ''
            }
          },
          {
            data: 'description'
          },
          {
            data: 'delivery_date',
            render: function(data) {
              return data ? new Date(data).toISOString().split('T')[0].split('-').reverse().join('-') : ''
            }
          },
          {
            data: 'status'
          },
          {
            data: 'created_at',
            render: function(data) {
              return new Date(data).toISOString().split('T')[0].split('-').reverse().join('-')
            }
          },
          {
            data: 'created_by'
          },
        ]
      })
      

      getCategory()
      getSegment()
      getModule()
      filterData()

      function getCategory() {
        $('#category-filter').select2({
          placeholder: 'Pilih kategori',
          ajax: {
            url: "{{ url('api/v1/category/indexForProduct') }}",
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

              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.category,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true
          }
        })
      }

      function getSegment() {
        let segments = []

        $('#segment-filter').select2({
          placeholder: 'Pilih segmen',
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
      }

      function getModule() {
        $('#module-filter').select2({
          placeholder: 'Pilih modul',
          ajax: {
            url: "{{ url('api/v1/module/index') }}",
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

              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.module_number,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true
          }
        })
      }

      function filterData() {
        $('.filter-product').on('change', () => {
          categoryFilter = $('#category-filter').val()
          segmentFilter = $('#segment-filter').val()
          moduleFilter = $('#module-filter').val()
          table.ajax.reload(null, false)

          // console.log(categoryFilter, segmentFilter, moduleFilter)
        })
      }

      $('#delete-filter-data').on('click', () => {
        categoryFilter = ''
        segmentFilter = ''
        moduleFilter = ''
        $('.filter-product').val(null).trigger('change')
        table.ajax.reload(null, false)
      })
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
      $('#modalExport').modal('show')

      let segment, category

      getCategory()
      getSegment()

      function getCategory() {
        $('#category-export').select2({
          placeholder: 'Pilih kategori',
          ajax: {
            url: "{{ url('api/v1/category/indexForProduct') }}",
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

              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.category,
                    id: item.id,
                  }
                }),
                pagination: {
                    more: data.page_info.last_page != params.page
                }
              }
            },
            cache: true
          }
        })
      }

      function getSegment() {
        $('#segment-export').select2({
          placeholder: 'Pilih segmen',
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

      }

      $('#segment-export').on('change', () => {
        const id = $('#segment-export').val()
        segment = id

        // const url = `{{ url('api/v1/product/report-product') }}?segment=${id}`
        // $('#export-excel').attr('href', url);
      })

      $('#category-export').on('change', () => {
        const id = $('#category-export').val()
        category = id
      })

      $('#export-form').on('submit', () => {
        event.preventDefault()

        const url = `{{ url('api/v1/product/report-product') }}?segment=${segment}&category=${category}`
        
        window.open(url, '_blank')
      })
    }

  </script>
@endsection
