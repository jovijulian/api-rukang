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
            <div class="search-input">
              <a class="btn btn-searchset"><img src="{{ url('assets/img/icons/search-white.svg') }}" alt="img"></a>
            </div>
          </div>
        </div>

        <div class="table-responsive pb-4">
          <table id="product-table" class="table">
            <thead>
              <tr>
                <th></th>
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

    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    let config = {
      headers: {
        'X-CSRF-TOKEN': token,
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Authorization': `${tokenType} ${accessToken}`
      }
    }
    
    if ("{{ $statusId }}" == 16) {
      $('#status-prev').hide()
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

            return json.data
          },
          error: function(err) {
            console.log(err)
          }
        },
        columnDefs: [
          {
            orderable: false,
            className: 'select-checkbox',
            orderable: false,
            targets: 0
          }
        ],
        select: {
          style: 'multi',
          // selector: 'td:first-child'
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

      let selectedId = []
      
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

        const rowData = table.rows('.selected').data()

        for (let i = 0; i < rowData.length; i++) {
          selectedId.push(rowData[i].id)
        }

        $('#statusNextModal').modal('show')
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
          current_status: "{{ $statusId }}",
          status_date: $('#status-date').val(),
          note: $('#note').val() ? $('#note').val() : '',
          current_location: $('#current-location').prop('disabled') ? '' : $('#current-location').val(),
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

    })

  </script>
@endsection
