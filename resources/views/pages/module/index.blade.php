@extends('layouts/content')

@section('title')
  <title>Modul</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Modul</h4>
        <h6>Manajemen Data Modul</h6>
      </div>
      <div class="page-btn">
        <a href="/module/insert" class="btn btn-added remove-role"><img src="{{ url('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Modul Baru</a>
      </div>
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
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel" onclick="toExcel()"><img src="{{ url('assets/img/icons/excel.svg') }}" alt="img"></a>
              </li>
            </ul>
          </div>
        </div>
        {{-- <!-- /Filter -->
        <div class="card mb-0" id="filter_inputs">
          <div class="card-body pb-0">
            <div class="row">
              <div class="col-lg-12 col-sm-12">
                <div class="row">
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Product</option>
                        <option>Macbook pro</option>
                        <option>Orange</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Category</option>
                        <option>Computers</option>
                        <option>Fruits</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Choose Sub Category</option>
                        <option>Computer</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12">
                    <div class="form-group">
                      <select class="select">
                        <option>Brand</option>
                        <option>N/D</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg col-sm-6 col-12 ">
                    <div class="form-group">
                      <select class="select">
                        <option>Price</option>
                        <option>150.00</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-1 col-sm-6 col-12">
                    <div class="form-group">
                      <a class="btn btn-filters ms-auto"><img src="{{ url('assets/img/icons/search-whites.svg') }}" alt="img"></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /Filter --> --}}
        <div class="table-responsive pb-4">
          <table id="description-table" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>No Modul</th>
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
      if(!currentUser.isAdmin) {
        window.location.href = "{{ url('/dashboard') }}"
      }

      // GET DATA
      const table = $('#description-table')


      getData()

      // GET DESCRIPTION
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
            url: "{{ url('api/v1/module/datatable') }}",
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
                return meta.row + meta.settings._iDisplayStart + 1
              },
            },
            {data: 'module_number'},
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
                    <a class="me-3" href="/module/edit/` + data + `">
                      <img src="assets/img/icons/edit.svg" alt="img">
                    </a>
                  `
                } else {
                  return `
                    <a class="me-3" href="/module/edit/` + data + `"  ${hiddenRole && 'hidden'}>
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
        title: 'Yakin ingin menghapus modul?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#global-loader').show()

          axios.delete(`{{ url('api/v1/module/delete/${id}') }}`, config)
            .then(res => {
              sessionStorage.setItem("success", "Modul berhasil dihapus")
              location.reload()
            })
            .catch(err => {
              Swal.fire('Modul gagal dihapus!', '', 'error')
              console.log(err)
            })
        }
      })
    }

    function toExcel() {
      $('#global-loader').show()

      let page = 1
      let datas = []

      function fetchData() {
        axios.get(`{{ url('api/v1/module/index?page=${page}') }}`, config)
          .then(res => {
            // console.log(res.data.data.items)
            datas = datas.concat(res.data.data.items)

            if (res.data.page_info.next_page_url) {
              page++
              fetchData()
            } else {
              const rows = datas.map(row => ({
                module_number: row.module_number,
                created_at: new Date(row.created_at).toISOString().split('T')[0].split('-').reverse().join('-')
              }))

              const date = new Date().toISOString().split('T')[0].split('-').reverse().join('-')

              let wb = new ExcelJS.Workbook()
              let workbookName = `laporan-modul-${date}.xlsx`
              let worksheetName = "Modul"
              let ws = wb.addWorksheet(worksheetName)

              ws.columns = [
                { 
                  key: "module_number", 
                  header: "Nomor Modul", 
                  width: 20,
                  style: {
                    alignment: { horizontal: "center" }
                  }
                },
                { 
                  key: "created_at", 
                  header: "Dibuat Pada", 
                  width: 30,
                  style: {
                    alignment: { horizontal: "center" }
                  }
                },
              ]

              ws.addRows(rows)
              
              ws.getRow(1).font = { bold: true }
              ws.eachRow({ includeEmpty: false }, function(row, rowNumber) {
                row.eachCell(function(cell) {
                  cell.border = { 
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' },
                  }
                })
              })

              wb.xlsx.writeBuffer()
                .then(function(buffer) {
                  $('#global-loader').hide()

                  saveAs(
                    new Blob([buffer], { type: "application/octet-stream" }),
                    workbookName
                    )

                  Swal.fire('Berhasil', 'Laporan modul berhasil didownload', 'success')
                })

            }
          })
          .catch(err => {
            console.log(err)
          })
      }
      fetchData()
    }
  </script>
@endsection
