@extends('layouts/content')

@section('title')
  <title>Tambah Produk</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Tambah Data Produk</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/product') }}">Produk</a></li>
              <li class="breadcrumb-item active">Tambah Produk</li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Page Header -->


      <div class="row">
        <div class="col-md-12">
          <div class="card">
            {{-- <div class="card-header">
              <h5 class="card-title">Two Column Horizontal Form</h5>
            </div> --}}
            <div class="card-body">
              <form id="insert-product-form">
                <h5 class="card-title mb-4">Kategori, Segmen, Kelompok</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kategori *</label>
                      <div class="col-lg-9">
                        <select id="category-product" class="form-control select" required>
                          {{-- <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option> --}}
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Segmen *</label>
                      <div class="col-lg-9">
                        <select id="segment-product" class="form-control select" required>
                          {{-- <option value="pilih segmen" selected="selected" disabled>Pilih segmen</option> --}}
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kelompok *</label>
                      <div class="col-lg-9">
                        <select id="group-product" class="form-control select" required>
                          {{-- <option value="Pilih kelompok" selected="selected" disabled>Pilih kelompok</option> --}}
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Warna Barcode</label>
                      <div class="col-lg-9">
                        <input type="text" id="barcode-color" class="form-control" placeholder="Pilih segmen" disabled>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tempat Segmen</label>
                      <div class="col-lg-9">
                        <input type="text" id="segment-place" class="form-control" placeholder="Masukan tempat segmen">
                      </div>
                    </div>
                  </div>
                </div>

                <h5 class="card-title mb-4">Produk</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Modul *</label>
                      <div class="col-lg-9">
                        <select id="module-product" class="form-control select" required>
                          {{-- <option value="pilih modul" selected="selected" disabled>Pilih modul</option> --}}
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Bilah *</label>
                      <div class="col-lg-9">
                        <select id="no-bilah" class="form-control select" required>
                          <option value="pilih bilah" selected="selected" disabled>Pilih nomor bilah</option>
                          <option value="B01">B01</option>
                          <option value="B02">B02</option>
                          <option value="B03">B03</option>
                          <option value="B04">B04</option>
                          <option value="B05">B05</option>
                          <option value="B06">B06</option>
                          <option value="B07">B07</option>
                          <option value="B08">B08</option>
                          <option value="B09">B09</option>
                          <option value="B10">B10</option>
                          <option value="B11">B11</option>
                          <option value="B12">B12</option>
                          <option value="B13">B13</option>
                          <option value="B14">B14</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Rak</label>
                      <div class="col-lg-9">
                        <select id="shelf" class="form-control select">
                          {{-- <option value="pilih modul" selected="selected" disabled>Pilih rak</option> --}}
                        </select>
                      </div>
                    </div>
                    {{-- <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Rak</label>
                      <div class="col-lg-9">
                        <input type="text" id="shelf-number" class="form-control" placeholder="Masukan nomor rak">
                      </div>
                    </div> --}}
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Mulai Produksi</label>
                      <div class="col-lg-9">
                        <input type="date" id="production-date" class="form-control text-sm">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Keterangan</label>
                      <div class="col-lg-9">
                        <textarea rows="3" cols="5" id="description" class="form-control" placeholder="Masukan keterangan"></textarea>
                      </div>
                    </div>
                    {{-- <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Pengiriman</label>
                      <div class="col-lg-9">
                        <input type="date" id="delivery-date" class="form-control text-sm">
                      </div>
                    </div> --}}
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Barcode *</label>
                      <div class="col-lg-9">
                        <input type="text" id="barcode-product" class="form-control" placeholder="Masukan barcode" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <svg id="barcode"></svg>
                    </div>
                  </div>
                </div>

                <h5 class="card-title mb-4">Status</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Status *</label>
                      <div class="col-lg-9">
                        <select id="status-product" class="form-control select" required>
                          {{-- <option value="pilih status" selected="selected" disabled>Pilih status</option> --}}
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Status *</label>
                      <div class="col-lg-9">
                        <input type="date" id="status-date" class="form-control text-sm" required>
                      </div>
                    </div>                    
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Ekspedisi</label>
                      <div class="col-lg-9">
                        <select id="shipping" class="form-control select" disabled>
                          {{-- <option value="pilih ekspedisi" selected="selected" disabled>Pilih ekspedisi</option> --}}
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Plat Nomor</label>
                      <div class="col-lg-9">
                        <input type="text" id="number-plate" class="form-control" placeholder="Masukan plat nomor" disabled>
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
                      <label class="col-lg-3 col-form-label">Upload Foto Status</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="file" id="image-status" accept="image/*">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row" id="image-preview">
                    </div>
                  </div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary px-5 py-3">Tambah Produk</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script>
    $(document).ready(function() {
      const currentUser = JSON.parse(localStorage.getItem('current_user'))
      const tokenType = localStorage.getItem('token_type')
      const accessToken = localStorage.getItem('access_token')


      // REDIRECT IF NOT ADMIN
      // if (!currentUser.isAdmin) {
      //   window.location.href = "{{ url('/dashboard') }}"
      // }

      let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      let config = {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'multipart/form-data',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      }

      $('#no-bilah').select2()

      getModule()
      getCategory()
      getGroup()
      getSegment()
      getStatus()
      getShipping()
      getShelf()

      barcode()

      $('#image-status').change(function() {
        const file = this.files[0]
        if (file) {
          let reader = new FileReader()
          reader.onload = function(event) {
            $('#image-preview img').remove()
            $('#image-preview').append(
              `<img src="${event.target.result}" alt="" class="mx-auto" alt="" style="height: 300px; width: auto">`
            )
          }
          reader.readAsDataURL(file)
        }
      })


      function getModule() {
        $('#module-product').select2({
          placeholder: "Pilih modul",
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

      function getCategory() {
        $('#category-product').select2({
          placeholder: "Pilih kategori",
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

      function getGroup() {
        $('#group-product').select2({
          placeholder: "Pilih kelompok",
          ajax: {
            url: "{{ url('api/v1/group/index') }}",
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
                    text: item.group_name,
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

        $('#segment-product').select2({
          placeholder: "Pilih segmen",
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

        $('#segment-product').on('change', () => {
          const id = $('#segment-product').val()
          const selectedSegment = segments.find(obj => obj.id == $('#segment-product').val())

          $('#barcode-color').val(selectedSegment.barcode_color)
        })
      }

      function getShelf() {
        $('#shelf').select2({
          placeholder: "Pilih rak",
          ajax: {
            url: "{{ url('api/v1/shelf/index') }}",
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
                    text: item.shelf_name,
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

      function getStatus() {
        $('#status-product').select2({
          placeholder: "Pilih status",
          ajax: {
            url: "{{ url('api/v1/status-product/index') }}",
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
              // console.log(data);
              return {
                results: $.map(data.data.items, function(item) {
                  return {
                    text: item.status,
                    id: item.id,
                    location: item.need_expedition
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
        
        $('#status-product').on('change', function(e) {
          const needExpedition = $(this).select2('data')[0].location
          if (needExpedition) {
            $('#shipping').removeAttr('disabled')

            $('#number-plate').removeAttr('disabled')
          } else {
            $('#shipping').select2("enable", false)
            $("#shipping").val(null).trigger("change")
            
            $('#number-plate').attr('disabled', 'disabled')
            $('#number-plate').val('')
          }
        })
      }

      function getShipping() {
        $('#shipping').select2({
          placeholder: "Pilih ekspedisi",
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

      function barcode() {
        let segment = ''
        let module = ''
        let bilah = ''
        let category = ''

        const barcode = $('#barcode-product')

        barcode.on('input', () => {
          JsBarcode("#barcode", barcode.val())
        })

        $('#segment-product').on('change', () => {
          segment = $('#segment-product').find("option:selected").text().split(" ")
          segment[0] = segment[0][0]
          segment[1] = segment[1].length === 1 ? '0' + segment[1] : segment[1]
          segment = segment.slice(0, 2)
          segment = segment.join('')

          generateBarcode(segment, module, bilah, category)
        })

        $('#module-product').on('change', () => {
          module = $('#module-product').find("option:selected").text().match(/[A-Z]+|\d+/g)
          module[0] = module[0][0]
          module[1] = module[1].length === 1 ? '0' + module[1] : module[1]
          module = module.slice(0, 2)
          module = module.join('')

          generateBarcode(segment, module, bilah, category)
        })

        $('#no-bilah').on('change', () => {
          bilah = $('#no-bilah').find("option:selected").text().match(/[A-Z]+|\d+/g)
          bilah[0] = bilah[0][0]
          bilah[1] = bilah[1].length === 1 ? '0' + bilah[1] : bilah[1]
          bilah = bilah.join('')

          generateBarcode(segment, module, bilah, category)
        })

        $('#category-product').on('change', () => {
          category = $('#category-product').find("option:selected").text().charAt(0)

          generateBarcode(segment, module, bilah, category)
        })
      }

      function generateBarcode(segment, module, bilah, category) {
        let barcode = segment + module + bilah + category

        $('#barcode-product').val(barcode)
        JsBarcode("#barcode", barcode)
      }

      
      $('#insert-product-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        insertData()
      })


      function insertData() {
        const data = {
          category_id: $('#category-product').val() ? $('#category-product').val() : '',
          category: $('#category-product').val() ? $('#category-product').find("option:selected").text() : '',
          segment_id: $('#segment-product').val() ? $('#segment-product').val() : '',
          segment_name: $('#segment-product').val() ? $('#segment-product').find("option:selected").text() : '',
          group_id: $('#group-product').val() ? $('#group-product').val() : '',
          group_name: $('#group-product').val() ? $('#group-product').find("option:selected").text() : '',
          segment_place: $('#segment-place').val() ? $('#segment-place').val() : '',
          barcode: $('#barcode-product').val(),
          module_id: $('#module-product').val() ? $('#module-product').val() : '',
          module_number: $('#module-product').val() ? $('#module-product').find("option:selected").text() : '',
          bilah_number: $('#no-bilah').val() ? $('#no-bilah').val() : '',
          start_production_date: $('#production-date').val(),
          finish_production_date: '',
          shelf_id: $('#shelf').val() ? $('#shelf').val() : '',
          shelf_name: $('#shelf').val() ? $('#shelf').find("option:selected").text() : '',
          description: $('#description').val() ? $('#description').val() : "",
          // delivery_date: $('#delivery-date').val(),
          status_id: $('#status-product').val() ? $('#status-product').val() : '',
          status: $('#status-product').val() ? $('#status-product').find("option:selected").text() : '',
          status_date: $('#status-date').val(),
          status_photo: $('#image-status')[0].files[0] ? $('#image-status')[0].files[0] : '',
          note: $('#note').val(),
          shipping_id: $('#shipping').val() ? $('#shipping').val() : '',
          shipping_name: $('#shipping').val() ? $('#shipping').find("option:selected").text() : '',
          number_plate: $('#number-plate').val() ? $('#number-plate').val() : '',
          current_location: $('#current-location').prop('disabled') ? '' : $('#current-location').val()
        }

        // console.log(data)
        // return


        axios.post("{{ url('api/v1/product/create') }}", data, config)
          .then(res => {
            const product = res.data.data.item
            sessionStorage.setItem("success", `Produk berhasil ditambahkan`)
            window.location.href = "{{ url('/product') }}"
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
              title: 'Produk gagal ditambahkan',
              text: errorMessage
            })
          })
      }

    })
  </script>
@endsection
