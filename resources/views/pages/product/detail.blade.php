@extends('layouts/content')

@section('title')
  <title>Detail Produk</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Detail Produk</h4>
        <h6>Informasi detail produk</h6>
      </div>
      <div class="page-btn">
        <a href="/product/add-status" class="btn btn-added update-status remove-role"><img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Status Produk</a>
      </div>
    </div>
    <!-- /add -->
    <div class="row">
      <div class="col-lg-8 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="productdetails">
              <ul class="product-bar">
                <li>
                  <h4>Kategori</h4>
                  <h6 id="category"></h6>
                </li>
                <li>
                  <h4>Segmen</h4>
                  <h6 id="segment"></h6>
                </li>
                <li>
                  <h4>Nomor Modul</h4>
                  <h6 id="module-number"></h6>
                </li>
                <li>
                  <h4>Nomor Bilah</h4>
                  <h6 id="bilah-number"></h6>
                </li>
                <li>
                  <h4>Nomor Rak</h4>
                  <h6 id="shelf-number"></h6>
                </li>
                <li>
                  <h4>Keterangan</h4>
                  <h6 id="description"></h6>
                </li>
                <li>
                  <h4>Tanggal Mulai Produksi</h4>
                  <h6 id="production-date"></h6>
                </li>
                <li>
                  <h4>Tanggal Selesai Produksi</h4>
                  <h6 id="production-finish-date"></h6>
                </li>
                <li>
                  <h4>Tanggal Pengiriman</h4>
                  <h6 id="delivery-date"></h6>
                </li>
                <li>
                  <h4>Catatan</h4>
                  <h6 id="product-note"></h6>
                </li>
                <li>
                  <h4>Kelompok</h4>
                  <h6 id="product-group"></h6>
                </li>
                <li>
                  <h4>Dibuat Pada</h4>
                  <h6 id="created-at"></h6>
                </li>
                <li>
                  <h4>Dibuat Oleh</h4>
                  <h6 id="created-by"></h6>
                </li>
                <li>
                  <h4>Diubah Pada</h4>
                  <h6 id="updated-at"></h6>
                </li>
                <li>
                  <h4>Diubah Oleh</h4>
                  <h6 id="updated-by"></h6>
                </li>
              </ul>
              <div class="table-responsive mt-5">
                <h4 class="mb-3">Log Status</h4>
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>Foto Status</th>
                      <th>Status</th>
                      <th>Ekspedisi</th>
                      <th>Plat Nomor</th>
                      <th>Catatan</th>
                      <th>Dibuat Pada</th>
                      <th>Dibuat Oleh</th>
                      <th>Diubah Pada</th>
                      <th>Diubah Oleh</th>
                    </tr>
                  </thead>
                  <tbody id="status-table">
                  </tbody>
                </table>
              </div>
              <div class="table-responsive mt-5">
                <h4 class="mb-3">Log Lokasi</h4>
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>Action</th>
                      <th>Lokasi Terkini</th>
                      <th>Status</th>
                      <th>Dibuat Pada</th>
                      <th>Dibuat Oleh</th>
                      <th>Diubah Pada</th>
                      <th>Diubah Oleh</th>
                    </tr>
                  </thead>
                  <tbody id="location-table">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="slider-product-details">
              <div class="w-100 border mx-auto p-5">
                <svg id="barcode" class="mx-auto w-100 h-16"></svg>
              </div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <img src="{{ url('assets/img/product/product69.jpg') }}" id="photo" alt="img">
            <p class="text-center mt-2">Foto terbaru</p>
          </div>
        </div>
      </div>
    </div>

    <!-- /add -->
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
      // if (!currentUser.isAdmin) {
      //   window.location.href = "{{ url('/dashboard') }}"
      // }


      axios.get("{{ url('api/v1/product/detail/' . $id) }}", config)
        .then(res => {
          const product = res.data.data.item

          console.log(product);

          $('.update-status').attr('href', '/product/add-status/' + product.id)

          JsBarcode("#barcode", product.barcode)

          $("#photo").attr("src", product.status_photo ? product.status_photo : "{{ url('assets/img/product/product69.jpg') }}")

          $('#category').text(product.category ? product.category : '')
          $('#segment').text(product.segment.segment_name ? product.segment.segment_name : '')
          $('#module-number').text(product.module.module_number ? product.module.module_number : '')
          $('#bilah-number').text(product.bilah_number ? product.bilah_number : '')
          $('#shelf-number').text(product.shelf   ? product.shelf.shelf_name  : '')
          $('#description').text(product.description ? product.description : '')
          $('#production-date').text(product.start_production_date ? new Date(product.start_production_date).toISOString().split('T')[0].split('-').reverse().join('-') : '')
          $('#production-finish-date').text(product.finish_production_date ? new Date(product.finish_production_date).toISOString().split('T')[0].split('-').reverse().join('-') : '')
          $('#delivery-date').text(product.delivery_date ? new Date(product.delivery_date).toISOString().split('T')[0].split('-').reverse().join('-') : '')
          $('#product-note').text(product.note ? product.note : '')
          $('#product-group').text(product.group_name ? product.group_name : '')
          $('#created-at').text(new Date(product.created_at).toISOString().split('T')[0].split('-').reverse().join('-'))
          $('#created-by').text(product.created_by)
          $('#updated-at').text(product.updated_at ? new Date(product.updated_at).toISOString().split('T')[0].split('-').reverse().join('-') : '')
          $('#updated-by').text(product.updated_by)
          
          product.status_logs.map((statusLog, i) => {
            $('#status-table').append(
              `
                <tr>
                  <td class="flex align-center">
                    <button onclick="detailPhoto('${statusLog.status_photo}', '${statusLog.status_photo2}', '${statusLog.status_photo3}', '${statusLog.status_photo4}', '${statusLog.status_photo5}', '${statusLog.status_photo6}', '${statusLog.status_photo7}', '${statusLog.status_photo8}', '${statusLog.status_photo9}', '${statusLog.status_photo10}')" class="p-2 btn btn-submit me-3">Lihat Foto</button>
                    <a href="/product/edit-status/${product.id}/${statusLog.id}" class="p-2 text-white" style="width: 50px" ${hiddenRole && 'hidden'}><img src="{{ asset('assets/img/icons/edit.svg') }}" alt="img" class="me-1"></a>
                  </td>
                  <td>${statusLog.status_name ? statusLog.status_name : ''}</td>
                  <td>${statusLog.shipping_name ? statusLog.shipping_name : ''}</td>
                  <td>${statusLog.number_plate ? statusLog.number_plate : ''}</td>
                  <td>${statusLog.note ? statusLog.note : ''}</td>
                  <td>${new Date(statusLog.created_at).toISOString().split('T')[0].split('-').reverse().join('-')}</td>
                  <td>${statusLog.created_by ? statusLog.created_by : ''}</td>
                  <td>${statusLog.updated_at ? new Date(statusLog.updated_at).toISOString().split('T')[0].split('-').reverse().join('-') : ''}</td>
                  <td>${statusLog.updated_by ? statusLog.updated_by : ''}</td>
                </tr>
              `
            )
          })

          product.location_logs.map((locationLog, i) => {
            const link = `<a href="{{ url('product/edit-location/` + locationLog.status_log_id + `') }}" class='p-2 btn btn-submit text-white'  ${hiddenRole && 'hidden'}>Update Lokasi</a>`

            $('#location-table').append(
              `
                <tr>
                  <td>${locationLog.current_location ? link : ''}</td>
                  <td>${locationLog.current_location ? locationLog.current_location : ''}</td>
                  <td>${locationLog.status.status_name ? locationLog.status.status_name : ''}</td>
                  <td>${new Date(locationLog.created_at).toISOString().split('T')[0].split('-').reverse().join('-')}</td>
                  <td>${locationLog.created_by ? locationLog.created_by : ''}</td>
                  <td>${locationLog.updated_at ? new Date(locationLog.updated_at).toISOString().split('T')[0].split('-').reverse().join('-') : ''}</td>
                  <td>${locationLog.updated_by ? locationLog.updated_by : ''}</td>
                </tr>
              `
            )
          })

        })
        .catch(err => {
          console.log(err)
        })
    })

    function detailPhoto(photo1, photo2, photo3, photo4, photo5, photo6, photo7, photo8, photo9, photo10) {
      const photos = [
        photo1 !== 'null' ? photo1 : null,
        photo2 !== 'null' ? photo2 : null,
        photo3 !== 'null' ? photo3 : null,
        photo4 !== 'null' ? photo4 : null,
        photo5 !== 'null' ? photo5 : null,
        photo6 !== 'null' ? photo6 : null,
        photo7 !== 'null' ? photo7 : null,
        photo8 !== 'null' ? photo8 : null,
        photo9 !== 'null' ? photo9 : null,
        photo10 !== 'null' ? photo10 : null,
      ]


      const imagesContainer = document.createElement('div')
      imagesContainer.classList.add('row', 'py-5', 'justify-content-center')

      // Membuat gambar-gambar dalam perulangan
      for (let i = 0; i < photos.length; i++) {
        const imageUrl = photos[i] ? photos[i] : "{{ url('assets/img/product/product1.jpg') }}"

        const image = document.createElement('img')
        image.classList.add('cursor-pointer', 'col-2', 'p-3')
        image.src = imageUrl
        image.style.width = '120px'
        image.onclick = () => previewPhoto(imageUrl)

        imagesContainer.appendChild(image)
      }

      // Menampilkan gambar-gambar dalam Swal
      Swal.fire({
        showConfirmButton: false,
        html: imagesContainer,
        width: 600
      })
    }

    function previewPhoto(url) {
      Swal.fire({
        showConfirmButton: false,
        imageUrl: url,
        imageWidth: 700,
        width: 700
      })
    }
  </script>
@endsection
