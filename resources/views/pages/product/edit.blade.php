@extends('layouts/content')

@section('title')
  <title>Edit Produk</title>
@endsection

@section('content')
  <div class="cardhead">
    <div class="content container-fluid">
      <!-- Page Header -->
      <div class="page-header">
        <div class="row">
          <div class="col">
            <h3 class="page-title">Edit Data Produk</h3>
            <ul class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{ url('/product') }}">Produk</a></li>
              <li class="breadcrumb-item active">Edit Produk</li>
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
              <form id="edit-product-form">
                <h5 class="card-title mb-4">Kategori & Segmen</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kategori</label>
                      <div class="col-lg-9">
                        <select id="category-product" class="form-control select">
                          <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Segmen</label>
                      <div class="col-lg-9">
                        <select id="segment-product" class="form-control select">
                          <option value="pilih segmen" selected="selected" disabled>Pilih segmen</option>
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
                        <input type="text" id="segment-place" class="form-control" placeholder="Pilih segmen" disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <h5 class="card-title mb-4">Produk</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Modul</label>
                      <div class="col-lg-9">
                        <select id="module-product" class="form-control select">
                          <option value="pilih modul" selected="selected" disabled>Pilih modul</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Bilah</label>
                      <div class="col-lg-9">
                        <select id="no-bilah" class="form-control select">
                          <option value="pilih bilah" selected="selected" disabled>Pilih nomor bilah</option>
                          <option value="B1">B1</option>
                          <option value="B2">B2</option>
                          <option value="B3">B3</option>
                          <option value="B4">B4</option>
                          <option value="B5">B5</option>
                          <option value="B6">B6</option>
                          <option value="B7">B7</option>
                          <option value="B8">B8</option>
                          <option value="B9">B9</option>
                          <option value="B10">B10</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Nomor Rak</label>
                      <div class="col-lg-9">
                        <input type="text" id="shelf-number" class="form-control" placeholder="Masukan nomor rak">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Produksi</label>
                      <div class="col-lg-9">
                        <input type="date" id="production-date" class="form-control text-sm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">1/0</label>
                      <div class="col-lg-9 my-auto">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input io" name="io-radio" type="radio" id="io-yes" value=1>
                          <label class="form-check-label" for="io-yes">
                            1
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input io" type="radio" name="io-radio" id="io-no" value=0>
                          <label class="form-check-label" for="io-no">
                            0
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Dimur/dibaut?</label>
                      <div class="col-lg-9 my-auto">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input nut-bolt" type="radio" name="nut-bolt" id="bolt-yes" value=1>
                          <label class="form-check-label" for="bolt-yes">
                            Ya
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input nut-bolt" type="radio" name="nut-bolt" id="bolt-no" value=0>
                          <label class="form-check-label" for="bolt-no">
                            Tidak
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Keterangan</label>
                      <div class="col-lg-9">
                        <select id="description-product" class="form-control select">
                          <option value="pilih keterangan" selected="selected" disabled>Pilih keterangan</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Pengiriman</label>
                      <div class="col-lg-9">
                        <input type="date" id="delivery-date" class="form-control text-sm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Barcode</label>
                      <div class="col-lg-9">
                        <input type="text" id="barcode-product" class="form-control" placeholder="Masukan barcode" required>
                      </div>
                      {{-- <p id="generate-barcode" class="col-lg-2 btn btn-primary">Acak</p> --}}
                    </div>
                    <div class="form-group row">
                      <svg id="barcode"></svg>
                    </div>
                  </div>
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-primary px-5 py-3">Edit Produk</button>
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
      if (!currentUser.isAdmin) {
        window.location.href = "{{ url('/dashboard') }}"
      }

      let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      let config = {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      }

      getModule()
      getCategory()
      getSegment()
      getDesc()
      getDetailProduct()

      barcode()


      $('#edit-product-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        updateData()
      })


      function getModule() {
        axios.get("{{ url('api/v1/module/index') }}", config)
          .then(res => {
            const modules = res.data.data.items
            modules.map(module => {
              $('#module-product').append(`<option value=${module.id}>${module.module_number}</option>`)
            })

          })
          .catch(err => {
            console.log(err)
          })
      }

      function getCategory() {
        axios.get("{{ url('api/v1/category/index') }}", config)
          .then(res => {
            const categories = res.data.data.items
            categories.map(category => {
              $('#category-product').append(`<option value=${category.id}>${category.category}</option>`)
            })

          })
          .catch(err => {
            console.log(err)
          })
      }

      function getSegment() {
        let segments = []

        axios.get("{{ url('api/v1/segment/index') }}", config)
          .then(res => {
            segments = res.data.data.items

            segments.map(segment => {
              $('#segment-product').append(`<option value=${segment.id}>${segment.segment_name}</option>`)
            })
          })
          .catch(err => {
            console.log(err)
          })

        $('#segment-product').on('change', () => {
          const id = $('#segment-product').val()
          const selectedSegment = segments.find(obj => obj.id == $('#segment-product').val())

          $('#barcode-color').val(selectedSegment.barcode_color)
          $('#segment-place').val(selectedSegment.segment_place)
        })
      }

      function getDesc() {
        axios.get("{{ url('api/v1/description/index') }}", config)
          .then(res => {
            const descriptions = res.data.data.items
            descriptions.map(description => {
              $('#description-product').append(
                `<option value=${description.id}>${description.description}</option>`)
            })

          })
          .catch(err => {
            console.log(err)
          })
      }

      function getDetailProduct() {
        axios.get("{{ url('api/v1/product/detail/' . $id) }}", config)
          .then(res => {
            const data = res.data.data.item

            $('#category-product').val(data.category_id).change()
            $('#segment-product').val(data.segment.id).change()
            $('#module-product').val(data.module.id).change()
            $('#no-bilah').val(data.bilah_number).change()
            $('#shelf-number').val(data.shelf_number)
            $('#production-date').val(data.production_date)
            data.quantity ? $("[name='io-radio'][value='1']").prop("checked", true) : $("[name='io-radio'][value='0']").prop("checked", true)
            data.nut_bolt ? $("[name='nut-bolt'][value='1']").prop("checked", true) : $("[name='nut-bolt'][value='0']").prop("checked", true)
          })
          .catch(err => {
            console.log(err)
          })
      }

      function barcode() {
        let segment = ''
        let module = ''
        let bilah = ''
        let category = ''

        const barcode = $('#barcode-product')
        barcode.on('input',() => {
          JsBarcode("#barcode", barcode.val())
        })

        $('#segment-product').on('change', () => {
          segment = $('#segment-product').find("option:selected").text().split(" ")
          segment[0] = segment[0][0]
          segment[1] = segment[1].length === 1 ? '0' + segment[1] : segment[1]
          segment = segment.join('')

          generateBarcode(segment, module, bilah, category)
        })

        $('#module-product').on('change', () => {
          module = $('#module-product').find("option:selected").text().match(/[A-Z]+|\d+/g)
          module[0] = module[0][0]
          module[1] = module[1].length === 1 ? '0' + module[1] : module[1]
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
        console.log(barcode)

        $('#barcode-product').val(barcode)
        JsBarcode("#barcode", barcode)
      }


      function updateData() {
        const data = {
          category_id: $('#category-product').val() ? $('#category-product').val() : '',
          category: $('#category-product').val() ? $('#category-product').find("option:selected").text() : '',
          segment_id: $('#segment-product').val() ? $('#segment-product').val() : '',
          segment_name: $('#segment-product').val() ? $('#segment-product').find("option:selected").text() : '',
          barcode: $('#barcode-product').val(),
          module_id: $('#module-product').val() ? $('#module-product').val() : '',
          module_number: $('#module-product').val() ? $('#module-product').find("option:selected").text() : '',
          bilah_number: $('#no-bilah').val(),
          production_date: $('#production-date').val(),
          shelf_number: $('#shelf-number').val(),
          quantity: $(".io:checked").val() ? $(".io:checked").val() : '',
          nut_bolt: $('.nut-bolt:checked').val() ? $('.nut-bolt:checked').val() : '',
          description_id: $('#description-product').val() ? $('#description-product').val() : "",
          description: $('#description-product').val() ? $('#description-product').find("option:selected").text() : "",
          delivery_date: $('#delivery-date').val()
        }

        // console.log(data)
        // return


        axios.put("{{ url('api/v1/product/update/' . $id) }}", data, config)
          .then(res => {
            const product = res.data.data.item
            sessionStorage.setItem("success", `Produk berhasil diedit`)
            window.location.href = "{{ url('/product') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Produk gagal diedit', '', 'error')
            console.log(err)
          })
      }

    })
  </script>
@endsection