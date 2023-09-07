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
                <h5 class="card-title mb-4">Kategori & Segmen</h5>
                <div class="row mb-4 gx-lg-5">
                  <div class="col-xl-6">
                    {{-- <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kategori</label>
                      <div class="col-lg-9">
                        <select id="category-product" class="form-control select">
                          <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option>
                        </select>
                      </div>
                    </div> --}}
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Kategori</label>
                      <div class="col-lg-9">
                        <select id="category-product" class="form-control select">
                          <option value="pilih kategori" selected="selected" disabled>Pilih kategori</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Warna Barcode</label>
                      <div class="col-lg-9">
                        <input type="text" id="barcode-color" class="form-control" placeholder="Pilih segmen" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Segmen</label>
                      <div class="col-lg-9">
                        <select id="segment-product" class="form-control select">
                          <option value="pilih segmen" selected="selected" disabled>Pilih segmen</option>
                        </select>
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
                        <input type="text" class="form-control" placeholder="Masukan nomor modul">
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
                        <input type="text" class="form-control" placeholder="Masukan nomor rak">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Produksi</label>
                      <div class="col-lg-9">
                        <input type="date" class="form-control text-sm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">1/0</label>
                      <div class="col-lg-9 my-auto">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="gender_male" value="option1">
                          <label class="form-check-label" for="gender_male">
                            Ya
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="gender_female" value="option2">
                          <label class="form-check-label" for="gender_female">
                            Tidak
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Dimur/dibaut?</label>
                      <div class="col-lg-9 my-auto">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="gender_male" value="option1">
                          <label class="form-check-label" for="gender_male">
                            Ya
                          </label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="gender" id="gender_female" value="option2">
                          <label class="form-check-label" for="gender_female">
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
                      <label class="col-lg-3 col-form-label">Tanggal Produksi</label>
                      <div class="col-lg-9">
                        <input type="date" class="form-control text-sm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Barcode</label>
                      <div class="col-lg-9">
                        <input type="text" id="barcode-product" class="form-control" placeholder="Masukan barcode">
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
                      <label class="col-lg-3 col-form-label">Status</label>
                      <div class="col-lg-9">
                        <select id="status-product" class="form-control select">
                          <option value="pilih status" selected="selected" disabled>Pilih status</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Tanggal Status</label>
                      <div class="col-lg-9">
                        <input type="date" class="form-control text-sm">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Catatan</label>
                      <div class="col-lg-9">
                        <textarea rows="3" cols="5" id="address" class="form-control" placeholder="Masukan catatan"></textarea>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-3 col-form-label">Upload Foto Proses</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="file" id="image-status" accept="image/*">
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="form-group row" id="image-preview">
                      {{-- <label class="col-lg-3 col-form-label">Upload Foto Proses</label>
                      <div class="col-lg-9">
                        <input class="form-control" type="file" id="image-status" accept="image/*">
                      </div> --}}
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

      getCategory()
      getSegment()
      getStatus()
      getDesc()

      barcode()

      $('#image-status').change(function(){
        const file = this.files[0];
        console.log(file);
        if (file){
          let reader = new FileReader()
          reader.onload = function(event){
            $('#image-preview img').remove()
            $('#image-preview').append(`<img src="${event.target.result}" alt="" class="mx-auto" alt="" style="height: 300px; width: auto">`)
          }
          reader.readAsDataURL(file);
        }
      })


      $('#insert-segment-form').on('submit', () => {
        event.preventDefault()
        $('#global-loader').show()

        const data = {
          segment_name: $('#segment-name').val(),
          segment_place: $('#segment-place').val(),
          barcode_color: $('#barcode-color').val(),
        }


        axios.post("{{ url('api/v1/segment/create') }}", data, config)
          .then(res => {
            const segmen = res.data.data.item
            sessionStorage.setItem("success", `Segmen ${segmen.segment_name} berhasil ditambahkan`)
            window.location.href = "{{ url('/segment') }}"
          })
          .catch(err => {
            $('#global-loader').hide()
            Swal.fire('Segmen gagal ditambahkan', '', 'error')
            console.log(err)
          })

      })


      function getCategory() {
        axios.get("{{ url('api/v1/category/index') }}", config)
          .then(res => {
            const categories = res.data.data.items
            categories.map(category => {
              console.log(category);
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

      function getStatus() {
        axios.get("{{ url('api/v1/status/index') }}", config)
          .then(res => {
            const statuses = res.data.data.items
            statuses.map(status => {
              $('#status-product').append(
                `<option value=${status.id}>${status.status}</option>`)
            })

          })
          .catch(err => {
            console.log(err)
          })
      }

      function barcode() {
        const barcode = $('#barcode-product')
        barcode.on('input',() => {
          JsBarcode("#barcode", barcode.val())
        })
      }

    })
  </script>
@endsection
