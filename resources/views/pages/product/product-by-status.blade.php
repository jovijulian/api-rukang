@extends('layouts/content')

@section('title')
  <title class="title-status-product">Produk By Status</title>
@endsection


@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4 class="title-status-product">Produk By Status</h4>
        <h6>Manajemen data produk sesuai status</h6>
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
              <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}" alt="img"></a>
            </div>
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
                  <div class="col-lg-1 col-sm-6 col-12">
                    <div class="form-group">
                      <a id="delete-filter-data" class="btn btn-filters bg-danger w-100"><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img"></a>
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
                <th class="text-center p-2"><input type="checkbox" id="select-all" class="cursor-pointer" /></th>
                <th>Barcode</th>
                <th>Keterangan</th>
                <th>Diubah Pada</th>
                <th>Diubah Oleh</th>
                {{-- <th>Tanggal Status Terkini</th> --}}
              </tr>
            </thead>
          </table>
        </div>

        <div class="mt-3 d-flex justify-content-between">
          <button id="status-prev" class="btn btn-primary px-4 py-3" data-toggle="modal" data-target="#statusPrevModal">Mundurkan Status</button>
          <button id="status-drop" class="btn btn-secondary px-4 py-3" data-toggle="modal" data-target="#statusDropModal">Drop Status</button>
          <button id="status-next" class="btn btn-primary px-4 py-3" data-toggle="modal" data-target="#statusNextModal">Lanjutkan Status</button>
        </div>

        <div class="modal fade" id="statusPrevModal" tabindex="-1" aria-labelledby="statusPrevModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
              <div class="modal-header">
                <h5 class="modal-title" id="statusPrevModalLabel">Mundurkan Status</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> --}}
              </div>
              <div class="modal-body">
                <form id="status-form-prev">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status Terkini</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control text-sm recent-status" disabled>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Status *</label>
                    <div class="col-lg-9">
                      <input type="date" id="status-date-prev" class="form-control text-sm" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Lokasi Terkini</label>
                    <div class="col-lg-9">
                      <input type="text" id="current-location-prev" class="form-control" placeholder="Masukan lokasi terkini">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Catatan</label>
                    <div class="col-lg-9">
                      <textarea rows="3" cols="5" id="note-prev" class="form-control" placeholder="Masukan catatan"></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Upload Foto Status (Maks 10 Foto)</label>
                    <div class="col-lg-9">
                      <input class="form-control mb-1" type="file" id="image-status-prev" accept="image/*" multiple>
                      <div id="image-preview-prev" class="mt-2 row"></div>
                    </div>
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary px-3 py-2">Mundurkan Status</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="statusDropModal" tabindex="-1" aria-labelledby="statusDropModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
              <div class="modal-header">
                <h5 class="modal-title" id="statusDropModalLabel">Drop Status</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> --}}
              </div>
              <div class="modal-body">
                <form id="status-form-drop">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status Terkini</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control text-sm recent-status" disabled>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status Menjadi *</label>
                    <div class="col-lg-9">
                      <select id="status-drop-select" class="form-control select" required>
                        <option selected="selected" disabled>Pilih status</option>
                        <option value="29">98. Duplikat</option>
                        <option value="28">99. Tidak Dipakai</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Status *</label>
                    <div class="col-lg-9">
                      <input type="date" id="status-date-drop" class="form-control text-sm" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Lokasi Terkini</label>
                    <div class="col-lg-9">
                      <input type="text" id="current-location-drop" class="form-control" placeholder="Masukan lokasi terkini">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Catatan</label>
                    <div class="col-lg-9">
                      <textarea rows="3" cols="5" id="note-drop" class="form-control" placeholder="Masukan catatan"></textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Upload Foto Status (Maks 10 Foto)</label>
                    <div class="col-lg-9">
                      <input class="form-control mb-1" type="file" id="image-status-drop" accept="image/*" multiple>
                      <div id="image-preview-drop" class="mt-2 row"></div>
                    </div>
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary px-3 py-2">Drop Status</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="statusNextModal" tabindex="-1" aria-labelledby="statusNextModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
              <div class="modal-header">
                <h5 class="modal-title" id="statusNextModalLabel">Lanjutkan Status</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button> --}}
              </div>
              <div class="modal-body">
                <form id="status-form-next">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Status Terkini</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control text-sm recent-status" disabled>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Tanggal Status *</label>
                    <div class="col-lg-9">
                      <input type="date" id="status-date" class="form-control text-sm" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Lokasi Terkini</label>
                    <div class="col-lg-9">
                      <input type="text" id="current-location" class="form-control" placeholder="Masukan lokasi terkini">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Catatan</label>
                    <div class="col-lg-9">
                      <textarea rows="3" cols="5" id="note" class="form-control" placeholder="Masukan catatan"></textarea>
                    </div>
                  </div>
                  <div id="upload-signature-input" class="form-group row">
                    <label class="col-lg-3 col-form-label">Upload Surat Jalan</label>
                    <div class="col-lg-9">
                      <input class="form-control mb-1" type="file" id="upload-signature" accept="image/*">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Upload Foto Status (Maks 10 Foto)</label>
                    <div class="col-lg-9">
                      <input class="form-control mb-1" type="file" id="image-status" accept="image/*" multiple>
                      <div id="image-preview" class="mt-2 row"></div>
                    </div>
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary px-3 py-2">Lanjutkan Status</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="statusNextModalTravel" tabindex="-1" aria-labelledby="statusNextModalTravelLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content p-3">
              <div class="modal-header">
                <h5 class="modal-title" id="statusNextModalTravelLabel">Lanjutkan Status (Persiapan Surat Jalan)</h5>
              </div>
              <div class="modal-body">
                <form id="status-form-next-travel">
                  <h5 class="card-title mt-4 mb-3">Status</h5>
                  <div class="row">
                    <div class="col-lg-6 pe-lg-4">
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Status Terkini</label>
                        <div class="col-lg-9">
                          <input type="text" class="form-control text-sm recent-status" disabled>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Status *</label>
                        <div class="col-lg-9">
                          <input type="date" id="status-date-travel" class="form-control text-sm" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Lokasi Terkini</label>
                        <div class="col-lg-9">
                          <input type="text" id="current-location-travel" class="form-control" placeholder="Masukan lokasi terkini">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 ps-lg-4">
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Catatan</label>
                        <div class="col-lg-9">
                          <textarea rows="3" cols="5" id="note-travel" class="form-control" placeholder="Masukan catatan"></textarea>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Upload Foto Status (Maks 10 Foto)</label>
                        <div class="col-lg-9">
                          <input class="form-control mb-1" type="file" id="image-status-travel" accept="image/*" multiple>
                          <div id="image-preview-travel" class="mt-2 row"></div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <h5 class="card-title mt-4 mb-3">Surat Jalan</h5>
                  <div class="row">
                    <div class="col-lg-6 pe-lg-4">
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Kepada *</label>
                        <div class="col-lg-9">
                          <textarea id="receiver" cols="30" rows="10" placeholder="Masukan penerima" required></textarea>
                          {{-- <input type="date" id="status-date" class="form-control text-sm" required> --}}
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Dikirim Oleh *</label>
                        <div class="col-lg-9">
                          <input type="text" id="from" class="form-control text-sm" placeholder="Masukan pengirim" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Diperiksa Oleh (Gudang)</label>
                        <div class="col-lg-9">
                          <input type="text" id="checked-by-gudang" class="form-control text-sm" placeholder="Masukan diperiksa oleh gudang">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Diperiksa Oleh (Keamanan)</label>
                        <div class="col-lg-9">
                          <input type="text" id="checked-by-keamanan" class="form-control text-sm" placeholder="Masukan diperiksa oleh keamanan">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Diperiksa Oleh (Produksi)</label>
                        <div class="col-lg-9">
                          <input type="text" id="checked-by-produksi" class="form-control text-sm" placeholder="Masukan diperiksa oleh produksi">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Diperiksa Oleh (Project Manager)</label>
                        <div class="col-lg-9">
                          <input type="text" id="checked-by-project-manager" class="form-control text-sm" placeholder="Masukan diperiksa oleh project manager">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Pengemudi</label>
                        <div class="col-lg-9">
                          <input type="text" id="driver" class="form-control text-sm" placeholder="Masukan pengemudi">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Diterima Oleh (Site Manager)</label>
                        <div class="col-lg-9">
                          <input type="text" id="received-by-site-manager" class="form-control text-sm" placeholder="Masukan diterima oleh site manager">
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 ps-lg-4">
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nomor *</label>
                        <div class="col-lg-9">
                          <input type="text" id="nomor-travel" class="form-control text-sm" placeholder="Pilih kendaraan" disabled required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal *</label>
                        <div class="col-lg-9">
                          <input type="date" id="travel-date" class="form-control text-sm" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Kendaraan *</label>
                        <div class="col-lg-9">
                          <select id="shipping-select" class="form-control select" required>
                            {{-- <option selected="selected" disabled>Pilih kendaraan</option> --}}
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">No Polisi *</label>
                        <div class="col-lg-9">
                          <input type="text" id="number-plate" class="form-control text-sm" placeholder="Masukan nomor polisi" required>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nama Pengemudi</label>
                        <div class="col-lg-9">
                          <input type="text" id="driver-name" class="form-control text-sm" placeholder="Masukan nama pengemudi">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Nomor Telepon Pengemudi</label>
                        <div class="col-lg-9">
                          <input type="text" id="driver-telp" class="form-control text-sm" placeholder="Masukan nomor telepon pengemudi">
                        </div>
                      </div>
                    </div>
                  </div>
                  <h5 class="card-title mt-4 mb-3">Daftar Barang</h5>
                  <div class="row">
                    <div class="table-responsive pb-4">
                      <table id="selected-data-table" class="table">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-3 py-2">Buat Surat Jalan</button>
                  </div>
                </form>
              </div>
            </div>
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

    $('#image-status').change(function(event) {
      if (this.files) {
        let fileAmount = this.files.length
        $('#image-preview img').remove()

        if (fileAmount > 10) {
          console.log('salah');
          Swal.fire('Maksimal upload 10 foto', '', 'error')
          $('#image-status').val('')
          return
        }
        
        for (let i = 0; i < fileAmount; i++) {
          let reader = new FileReader()
          reader.onload = function(){
            $('#image-preview').append(`<img src="${this.result}" alt="" class="mx-auto col-2 m-1" alt="" style="height: 70px; width: auto">`)
          }
          reader.readAsDataURL(this.files[i])
        }
      }
    })

    $('#image-status-drop').change(function(event) {
      if (this.files) {
        let fileAmount = this.files.length
        $('#image-preview-drop img').remove()

        if (fileAmount > 10) {
          console.log('salah');
          Swal.fire('Maksimal upload 10 foto', '', 'error')
          $('#image-status-drop').val('')
          return
        }
        
        for (let i = 0; i < fileAmount; i++) {
          let reader = new FileReader()
          reader.onload = function(){
            $('#image-preview-drop').append(`<img src="${this.result}" alt="" class="mx-auto col-2 m-1" alt="" style="height: 70px; width: auto">`)
          }
          reader.readAsDataURL(this.files[i])
        }
      }
    })

    $('#image-status-prev').change(function(event) {
      if (this.files) {
        let fileAmount = this.files.length
        $('#image-preview img').remove()

        if (fileAmount > 10) {
          console.log('salah');
          Swal.fire('Maksimal upload 10 foto', '', 'error')
          $('#image-status-prev').val('')
          return
        }
        
        for (let i = 0; i < fileAmount; i++) {
          let reader = new FileReader()
          reader.onload = function(){
            $('#image-preview-prev').append(`<img src="${this.result}" alt="" class="mx-auto col-2 m-1" alt="" style="height: 70px; width: auto">`)
          }
          reader.readAsDataURL(this.files[i])
        }
      }
    })

    $('#image-status-travel').change(function(event) {
      if (this.files) {
        let fileAmount = this.files.length
        $('#image-preview-travel img').remove()

        if (fileAmount > 10) {
          console.log('salah');
          Swal.fire('Maksimal upload 10 foto', '', 'error')
          $('#image-status-travel').val('')
          return
        }
        
        for (let i = 0; i < fileAmount; i++) {
          let reader = new FileReader()
          reader.onload = function(){
            $('#image-preview-travel').append(`<img src="${this.result}" alt="" class="mx-auto col-2 m-1" alt="" style="height: 70px; width: auto">`)
          }
          reader.readAsDataURL(this.files[i])
        }
      }
    })

    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    let config = {
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'multipart/form-data',
        'Accept': 'application/json',
        'Authorization': `${tokenType} ${accessToken}`
      }
    }
    
    if ("{{ $statusId }}" == 16) {
      $('#status-prev').hide()
    }

    if ("{{ $statusId }}" == 21) {
      $('#status-next').hide()
    }

    $("#driver-telp").keypress(function(e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    })


    $(document).ready(function() {
      // NOTIF VERIFY USER
      const success = sessionStorage.getItem("success")
      if (success) {
        Swal.fire(success, '', 'success')
        sessionStorage.removeItem("success")
      }

      let categoryFilter = $('#category-filter').val()

      // GET PRODUCT
      const table = $('#product-table').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        bInfo: true,
        sDom: 'frBtlpi',
        ordering: true,
        pagingType: 'numbers',
        // stateSave: true,
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
          url: "{{ url('api/v1/product/datatable-product-status?status_id=' . $statusId) }}",
          dataType: 'json',
          type: 'POST',
          headers: {
            'X-CSRF-TOKEN': token,
            'Authorization': `${tokenType} ${accessToken}`
          },
          dataSrc: function (json) {
            $('.title-status-product').text(`Produk - ${json.status}`)
            $('.recent-status').val(`${json.status}`)

            console.log(json);

            return json.data
          },
          data: function(d) {
            d.category = categoryFilter
            return d
          },
          error: function(err) {
            console.log(err)
          }
        },
        columnDefs: [
          {
            targets: 0,
            checkboxes: true,
            sorting: false,
            orderable: false,
            className: 'select-checkbox',
            width: '5%'
          }
        ],
        select: {
          style: 'multi',
        },
        columns: [
          {
            data: null,
            orderable: false,
            searchable: false,
            render: function () {
                return '';
            },
          },
          {
            data: 'barcode'
          },
          {
            data: 'description'
          },
          {
            data: 'updated_at',
            render: function(data) {
              return data ? new Date(data).toISOString().split('T')[0].split('-').reverse().join('-') : ''
            }
          },
          {
            data: 'updated_by'
          },
        ]
      })

      let statusNextId
      let statusPrevId

      if ("{{ $statusId }}" == 16) {
        statusNextId = 17
      } else if ("{{ $statusId }}" == 17) {
        statusNextId = 18
      } else if ("{{ $statusId }}" == 18) {
        statusNextId = 19
      } else if ("{{ $statusId }}" == 19) {
        statusNextId = 32
      } else if ("{{ $statusId }}" == 32) {
        statusNextId = 20
      } else if ("{{ $statusId }}" == 20) {
        statusNextId = 21
      }

      if ("{{ $statusId }}" == 21) {
        statusPrevId = 20
      } else if ("{{ $statusId }}" == 20) {
        statusPrevId = 32
      } else if ("{{ $statusId }}" == 32) {
        statusPrevId = 19
      } else if ("{{ $statusId }}" == 19) {
        statusPrevId = 18
      } else if ("{{ $statusId }}" == 17) {
        statusPrevId = 16
      }

      // console.log(statusPrevId);

      getCategory()
      getShipping()
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

      function getShipping() {
        $('#shipping-select').select2({
          placeholder: "Pilih kendaraan",
          ajax: {
            url: "{{ url('api/v1/shipping/index') }}",
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
                    text: item.shipping_name,
                    id: item.id,
                    shippingName: item.shipping_name + ' - ' + item.shipping_use
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


      $('#shipping-select').on('change', function (e) {
        const selectedData = $(this).select2('data')
        if (selectedData.length > 0) {
          const shippingName = selectedData[0].shippingName
          $('#nomor-travel').val(shippingName)
          console.log('Shipping name:', shippingName)
        }
      });

      function filterData() {
        $('#category-filter').on('change', () => {
          categoryFilter = $('#category-filter').val()

          table.ajax.reload(null, false)
        })
      }

      $('#delete-filter-data').on('click', () => {
        categoryFilter = ''
        $('#category-filter').val(null).trigger('change')
        table.ajax.reload(null, false)
      })



      let selectedId = []
      let selectedData = []

      $("#select-all").on("click", function(e) {
          if ($(this).is(":checked")) {
              table.rows().select();
          } else {
              table.rows().deselect();
          }
      });
      
      $('#status-prev').on('click', () => {
        selectedId = []

        const rowData = table.rows('.selected').data()

        for (let i = 0; i < rowData.length; i++) {
          selectedId.push(rowData[i].id)
        }

        $('#statusPrevModal').modal('show')
      })

      $('#status-drop').on('click', () => {
        selectedId = []

        const rowData = table.rows('.selected').data()

        for (let i = 0; i < rowData.length; i++) {
          selectedId.push(rowData[i].id)
        }

        $('#statusDropModal').modal('show')
      })

      $('#status-next').on('click', () => {
        selectedId = []
        selectedData = []

        const rowData = table.rows('.selected').data()

        for (let i = 0; i < rowData.length; i++) {
          selectedId.push(rowData[i].id)
          selectedData.push(rowData[i])
        }

        if ("{{ $statusId }}" == 19) {
          $('#statusNextModalTravel').modal('show')

          $('#selected-data-table > tbody').empty()

          selectedData.map((data, i) => {
            const selectedDataTable = $('#selected-data-table tbody')
            const rowMarkup = `
              <tr>
                <th>${i + 1}</th>
                <th>${data.category + ' - ' + data.barcode}</th>
              </tr>
            `
            selectedDataTable.append(rowMarkup)
          })

        } else if ("{{ $statusId }}" == 32 || "{{ $statusId }}" == 20) {
          $('#statusNextModal').modal('show')          
        } else {
          $('#upload-signature-input').hide()
          $('#statusNextModal').modal('show')
        }
      })


      $('#status-form-prev').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        if (selectedId.length == 0) {
          $('#global-loader').hide()

          Swal.fire({
            icon: 'error',
            title: 'Status produk gagal dimundurkan',
            text: 'Tolong pilih produk terlebih dahulu'
          })

          return
        }

        const data = {
          selected_product: selectedId,
          current_status: "{{ $statusId }}",
          previous_status: statusPrevId,
          status_date: $('#status-date-prev').val(),
          note: $('#note-prev').val() ? $('#note-prev').val() : '',
          current_location: $('#current-location-prev').prop('disabled') ? '' : $('#current-location-prev').val(),
          status_photo: $('#image-status-prev')[0].files[0] ? $('#image-status-prev')[0].files[0] : '',
          status_photo2: $('#image-status-prev')[0].files[1] ? $('#image-status-prev')[0].files[1] : '',
          status_photo3: $('#image-status-prev')[0].files[2] ? $('#image-status-prev')[0].files[2] : '',
          status_photo4: $('#image-status-prev')[0].files[3] ? $('#image-status-prev')[0].files[3] : '',
          status_photo5: $('#image-status-prev')[0].files[4] ? $('#image-status-prev')[0].files[4] : '',
          status_photo6: $('#image-status-prev')[0].files[5] ? $('#image-status-prev')[0].files[5] : '',
          status_photo7: $('#image-status-prev')[0].files[6] ? $('#image-status-prev')[0].files[6] : '',
          status_photo8: $('#image-status-prev')[0].files[7] ? $('#image-status-prev')[0].files[7] : '',
          status_photo9: $('#image-status-prev')[0].files[8] ? $('#image-status-prev')[0].files[8] : '',
          status_photo10: $('#image-status-prev')[0].files[9] ? $('#image-status-prev')[0].files[9] : '',
        }

        // console.log(data)
        // return

        axios.post("{{ url('api/v1/product/rewind-status') }}", data, config)
          .then(res => {
            // const produk = res.data.data.item
            sessionStorage.setItem("success", `Status produk berhasil dimundurkan`)
            window.location.href = `{{ url('/product-by-status/' . $statusId) }}`
            // window.location.reload()
          })
          .catch(err => {
            $('#global-loader').hide()
            
            let errorMessage = ''

            if (err.response.status == 422) {
              const errors = err.response.data.errors[0]
              for (const key in errors) {
                errorMessage += `${errors[key]} \n`
              }
            } else if(err.response.status == 500) {
              errorMessage = 'Internal server error'
            }

            Swal.fire({
              icon: 'error',
              title: 'Status produk gagal dimundurkan',
              // text: errorMessage
            })
          })
      })

      $('#status-form-drop').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        if (selectedId.length == 0) {
          $('#global-loader').hide()

          Swal.fire({
            icon: 'error',
            title: 'Status produk gagal didrop',
            text: 'Tolong pilih produk terlebih dahulu'
          })
          return
        }

        const data = {
          selected_product: selectedId,
          status_id: $('#status-drop-select').val() ? $('#status-drop-select').val() : '',
          status_name: $('#status-drop-select').val() ? $('#status-drop-select').find("option:selected").text() : '',
          status_date: $('#status-date-drop').val(),
          note: $('#note-drop').val() ? $('#note-drop').val() : '',
          current_location: $('#current-location-drop').prop('disabled') ? '' : $('#current-location-drop').val(),
          status_photo: $('#image-status-drop')[0].files[0] ? $('#image-status-drop')[0].files[0] : '',
          status_photo2: $('#image-status-drop')[0].files[1] ? $('#image-status-drop')[0].files[1] : '',
          status_photo3: $('#image-status-drop')[0].files[2] ? $('#image-status-drop')[0].files[2] : '',
          status_photo4: $('#image-status-drop')[0].files[3] ? $('#image-status-drop')[0].files[3] : '',
          status_photo5: $('#image-status-drop')[0].files[4] ? $('#image-status-drop')[0].files[4] : '',
          status_photo6: $('#image-status-drop')[0].files[5] ? $('#image-status-drop')[0].files[5] : '',
          status_photo7: $('#image-status-drop')[0].files[6] ? $('#image-status-drop')[0].files[6] : '',
          status_photo8: $('#image-status-drop')[0].files[7] ? $('#image-status-drop')[0].files[7] : '',
          status_photo9: $('#image-status-drop')[0].files[8] ? $('#image-status-drop')[0].files[8] : '',
          status_photo10: $('#image-status-drop')[0].files[9] ? $('#image-status-drop')[0].files[9] : '',
        }

        // console.log(data)
        // return

        axios.post("{{ url('api/v1/product/drop-status') }}", data, config)
          .then(res => {
            // const produk = res.data.data.item
            sessionStorage.setItem("success", `Status produk berhasil didrop`)
            window.location.href = `{{ url('/product-by-status/' . $statusId) }}`
            // window.location.reload()
          })
          .catch(err => {
            $('#global-loader').hide()
            
            let errorMessage = ''

            if (err.response.status == 422) {
              const errors = err.response.data.errors[0]
              for (const key in errors) {
                errorMessage += `${errors[key]} \n`
              }
            } else if(err.response.status == 500) {
              errorMessage = 'Internal server error'
            }

            Swal.fire({
              icon: 'error',
              title: 'Status produk gagal didrop',
              // text: errorMessage
            })
          })
      })


      $('#status-form-next').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        if (selectedId.length == 0) {
          $('#global-loader').hide()

          Swal.fire({
            icon: 'error',
            title: 'Status produk gagal dilanjutkan',
            text: 'Tolong pilih produk terlebih dahulu'
          })

          return
        }

        const data = {
          selected_product: selectedId,
          next_status: statusNextId,
          current_status: "{{ $statusId }}",
          status_date: $('#status-date').val(),
          note: $('#note').val() ? $('#note').val() : '',
          current_location: $('#current-location').prop('disabled') ? '' : $('#current-location').val(),
          upload_signature: $('#upload-signature')[0].files[0] ? $('#upload-signature')[0].files[0] : '',
          status_photo: $('#image-status')[0].files[0] ? $('#image-status')[0].files[0] : '',
          status_photo2: $('#image-status')[0].files[1] ? $('#image-status')[0].files[1] : '',
          status_photo3: $('#image-status')[0].files[2] ? $('#image-status')[0].files[2] : '',
          status_photo4: $('#image-status')[0].files[3] ? $('#image-status')[0].files[3] : '',
          status_photo5: $('#image-status')[0].files[4] ? $('#image-status')[0].files[4] : '',
          status_photo6: $('#image-status')[0].files[5] ? $('#image-status')[0].files[5] : '',
          status_photo7: $('#image-status')[0].files[6] ? $('#image-status')[0].files[6] : '',
          status_photo8: $('#image-status')[0].files[7] ? $('#image-status')[0].files[7] : '',
          status_photo9: $('#image-status')[0].files[8] ? $('#image-status')[0].files[8] : '',
          status_photo10: $('#image-status')[0].files[9] ? $('#image-status')[0].files[9] : '',
        }

        // console.log(data)
        // return

        axios.post("{{ url('api/v1/product/continue-status') }}", data, config)
          .then(res => {
            // const produk = res.data.data.item
            sessionStorage.setItem("success", `Status produk berhasil dilanjutkan`)
            window.location.href = `{{ url('/product-by-status/' . $statusId) }}`
            // window.location.reload()
          })
          .catch(err => {
            $('#global-loader').hide()
            
            let errorMessage = ''

            if (err.response.status == 422) {
              const errors = err.response.data.errors[0]
              for (const key in errors) {
                errorMessage += `${errors[key]} \n`
              }
            } else if(err.response.status == 500) {
              errorMessage = 'Internal server error'
            }

            Swal.fire({
              icon: 'error',
              title: 'Status produk gagal dilanjutkan',
              // text: errorMessage
            })
          })
      })

      $('#status-form-next-travel').on('submit', () => {
        $('#global-loader').show()
        event.preventDefault()

        const satuan = $('.satuan').map((_,el) => el.value).get()
        const packing = $('.packing').map((_,el) => el.value).get()
        const keterangan = $('.keterangan').map((_,el) => el.value).get()

        const finalDataSelected = selectedData.map((item, i) => ({
          productName: `${item.category} - ${item.barcode}`,
        }))


        const data = {
          selected_product: selectedId,
          next_status: statusNextId,
          current_status: "{{ $statusId }}",
          status_date: $('#status-date-travel').val(),
          note: $('#note-travel').val() ? $('#note-travel').val() : '',
          current_location: $('#current-location-travel').prop('disabled') ? '' : $('#current-location-travel').val(),
          status_photo: $('#image-status-travel')[0].files[0] ? $('#image-status-travel')[0].files[0] : '',
          status_photo2: $('#image-status-travel')[0].files[1] ? $('#image-status-travel')[0].files[1] : '',
          status_photo3: $('#image-status-travel')[0].files[2] ? $('#image-status-travel')[0].files[2] : '',
          status_photo4: $('#image-status-travel')[0].files[3] ? $('#image-status-travel')[0].files[3] : '',
          status_photo5: $('#image-status-travel')[0].files[4] ? $('#image-status-travel')[0].files[4] : '',
          status_photo6: $('#image-status-travel')[0].files[5] ? $('#image-status-travel')[0].files[5] : '',
          status_photo7: $('#image-status-travel')[0].files[6] ? $('#image-status-travel')[0].files[6] : '',
          status_photo8: $('#image-status-travel')[0].files[7] ? $('#image-status-travel')[0].files[7] : '',
          status_photo9: $('#image-status-travel')[0].files[8] ? $('#image-status-travel')[0].files[8] : '',
          status_photo10: $('#image-status-travel')[0].files[9] ? $('#image-status-travel')[0].files[9] : '',

          shipping_id: $('#shipping-select').val() ? $('#shipping-select').val() : '',
          shipping_name: $('#shipping-select').val() ? $('#shipping-select').find("option:selected").text() : '',
          
          receiver: $('#receiver').val() ? $('#receiver').val() : '',
          from: $('#from').val() ? $('#from').val() : '',
          checked_by_gudang: $('#checked-by-gudang').val() ? $('#checked-by-gudang').val() : '',
          checked_by_keamanan: $('#checked-by-keamanan').val() ? $('#checked-by-keamanan').val() : '',
          checked_by_produksi: $('#checked-by-produksi').val() ? $('#checked-by-produksi').val() : '',
          checked_by_project_manager: $('#checked-by-project-manager').val() ? $('#checked-by-project-manager').val() : '',
          driver: $('#driver').val() ? $('#driver').val() : '',
          received_by_site_manager: $('#received-by-site-manager').val() ? $('#received-by-site-manager').val() : '',
          nomor_travel: $('#nomor-travel').val() ? $('#nomor-travel').val() : '',
          travel_date: $('#travel-date').val() ? $('#travel-date').val() : '',
          number_plate: $('#number-plate').val() ? $('#number-plate').val() : '',
          driver_name: $('#driver-name').val() ? $('#driver-name').val() : '',
          driver_telp: $('#driver-telp').val() ? $('#driver-telp').val() : '',

          products: finalDataSelected
        }

        // console.log(data)
        // return

        if (selectedId.length == 0) {
          $('#global-loader').hide()

          Swal.fire({
            icon: 'error',
            title: 'Status produk gagal dilanjutkan',
            text: 'Tolong pilih produk terlebih dahulu'
          })

          return
        }

        axios.post("{{ url('api/v1/product/generate-travel-document') }}", data, {
          headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json',
            'Authorization': `${tokenType} ${accessToken}`,
          },
          responseType: 'blob'
        })
          .then(res => {
            // const produk = res.data.data.item
            // sessionStorage.setItem("success", `Status produk berhasil dilanjutkan`)
            // window.location.href = `{{ url('/product-by-status/' . $statusId) }}`
            // window.location.reload()

            console.log(res);
          })
          .catch(err => {
            $('#global-loader').hide()

            console.log(err);
            
            let errorMessage = ''

            if (err.response.status == 422) {
              const errors = err.response.data.errors[0]
              for (const key in errors) {
                errorMessage += `${errors[key]} \n`
              }
            } else if(err.response.status == 500) {
              errorMessage = 'Internal server error'
            }

            Swal.fire({
              icon: 'error',
              title: 'Status produk gagal dilanjutkan',
              // text: errorMessage
            })
          })
      })

    })

  </script>
@endsection
