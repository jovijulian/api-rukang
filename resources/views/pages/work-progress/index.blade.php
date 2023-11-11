@extends('layouts/content')

@section('title')
  <title>Progress Pekerjaan</title>
@endsection

@section('content')
  <div class="content">
    <div class="page-header">
      <div class="page-title">
        <h4>Progress Pekerjaan</h4>
        <h6>Manajemen Data Progress Pekerjaan</h6>
      </div>
      <div class="page-btn">
        <a href="/work-progress/insert" class="btn btn-added remove-role"><img src="{{ url('assets/img/icons/plus.svg') }}" alt="img" class="me-1">Tambah Progress Pekerjaan Baru</a>
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
          <div class="wordset">
            <ul>
              <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="{{ url('assets/img/icons/excel.svg') }}" alt="img"></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive pb-4">
          <table id="shelf-table" class="table">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Progress</th>
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

    <div class="modal fade" id="detailPhoto" tabindex="-1" aria-labelledby="detailPhotoLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
          <div class="modal-header flex justify-content-between">
            <h5 class="modal-title" id="detailPhotoLabel">Detail Foto</h5>
            <div class="flex">
              <a id="update-all" class="p-2 btn btn-primary p-2 text-white me-2" >Ubah Semua</a>
              <button id="delete-all-image" class="p-1 btn btn-danger p-2 text-white" >Hapus Semua</button>
            </div>
          </div>
          <div class="modal-body">
            <div id="row-photo" class="row justify-content-center">
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

    let dataProgress = []

    $(document).ready(function() {
      // NOTIF VERIFY USER
      const success = sessionStorage.getItem("success")
      if (success) {
        Swal.fire(success, '', 'success')
        sessionStorage.removeItem("success")
      }

      // REDIRECT IF NOT ADMIN
      // if(!currentUser.isAdmin) {
      //   window.location.href = "{{ url('/dashboard') }}"
      // }

      // GET DATA
      const table = $('#shelf-table')

      getData()

      // GET SHELF
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
            dataProgress = json.data
            $('.dataTables_filter').appendTo('.search-input')
          },
          ajax: {
            url: "{{ url('api/v1/work-progress/datatable') }}",
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
            {data: 'process_name'},
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
                    <a class="me-3" href="/work-progress/edit/` + data + `">
                      <img src="assets/img/icons/edit.svg" alt="img">
                    </a>
                    <button onclick="detailPhotoModal(` + data + `)" class="p-2 btn btn-submit text-white" >Detail Foto</button>
                  `
                } else {
                  return `
                    <a class="me-3" href="/work-progress/edit/` + data + `"  ${hiddenRole && 'hidden'}>
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

    function detailPhotoModal(idProgress) {
      const dataFilter = dataProgress.filter((data) => data.id === idProgress)

      $('#row-photo').empty()
      
      $('#update-all').attr('href', `/work-progress/update-image/${dataFilter[0].id}`)
      $('#delete-all-image').attr('onclick',`deleteAllFile('${dataFilter[0].id}')`)
      
      const photos = {
        photo_01: dataFilter[0].photo_01 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_02: dataFilter[0].photo_02 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_03: dataFilter[0].photo_03 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_04: dataFilter[0].photo_04 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_05: dataFilter[0].photo_05 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_06: dataFilter[0].photo_06 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_07: dataFilter[0].photo_07 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_08: dataFilter[0].photo_08 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_09: dataFilter[0].photo_09 || "{{ url('assets/img/product/product1.jpg') }}",
        photo_10: dataFilter[0].photo_10 || "{{ url('assets/img/product/product1.jpg') }}",
      }

      for (const photo in photos) {
        $('#row-photo').append(`
          <div class="col-sm-6 col-md-4 col-lg-3 p-3 mb-2">
            <div class="p-3" style="height:150px">
              <img src=${photos[photo]} class="m-auto" style="width:auto;height:auto" alt="img">
            </div>
            <div class="p-3">
              <button onclick="singleFileUpload('${photo}', '${dataFilter[0].id}')" class="p-2 btn btn-primary w-100 text-white py-1 mb-1" >Ubah Foto</button>
              <button onclick="deleteSingleFile('${photo}', '${dataFilter[0].id}')" class="p-1 btn btn-danger w-100 text-white" >Hapus Foto</button>
            </div>
          </div>
        `)
      }

      $('#detailPhoto').modal('show')
    }

    function singleFileUpload(field, id) {
      const el = `
        <input class="form-control mt-5 mb-1" type="file" id="single-image-process" accept="image/*" required>
      `
      Swal.fire({
        title: "Ubah Foto",
        html: el,
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Kembali',
      }).then((result) => {
        if (result.isConfirmed) {
          const file = $('#single-image-process')[0].files[0]
          if (file) {
            $('#global-loader').show()
            const data = {
              [field]: file
            };

            uploadPhotoProgress(data, id)
          } else {
            $('#global-loader').hide()

            Swal.fire({
              icon: 'warning',
              title: 'Tolong masukan foto'
            })
          }
        }
      });
    }

    function uploadPhotoProgress(data, id) {
      axios.post(`{{ url('api/v1/work-progress/update-image-work-progress/${id}') }}`, data, {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'multipart/form-data',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      })
        .then(res => {
          sessionStorage.setItem("success", `Foto Progress Pekerjaan berhasil diubah`)
          window.location.href = "{{ url('/work-progress') }}"
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
            title: 'Foto Progress Pekerjaan gagal ditambahkan',
            text: errorMessage
          })
        })
    }

    function deleteAllFile(id) {
      const data = {
        selected_image: ['photo_01', 'photo_02', 'photo_03', 'photo_04', 'photo_05', 'photo_06', 'photo_07', 'photo_08', 'photo_10']
      }

      Swal.fire({
        title: 'Yakin ingin menghapus semua foto?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#global-loader').show()

          deleteFile(data, id)
        }
      })
    }

    function deleteSingleFile(field, id) {
      Swal.fire({
        title: 'Yakin ingin menghapus foto?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#global-loader').show()

          let data = {
            selected_image: []
          }

          data.selected_image.push(field)

          deleteFile(data, id)
        }
      })
    }

    function deleteFile(data, id) {
      axios.post(`{{ url('api/v1/work-progress/delete-selected-image-work-progress/${id}') }}`, data , {
        headers: {
          'X-CSRF-TOKEN': token,
          'Content-Type': 'multipart/x-www-form-urlencoded',
          'Accept': 'application/json',
          'Authorization': `${tokenType} ${accessToken}`
        }
      })
        .then(res => {
          sessionStorage.setItem("success", "Foto berhasil dihapus")
          location.reload()
        })
        .catch(err => {
          Swal.fire('Rak gagal dihapus!', '', 'error')
          console.log(err)
        })
    }
  </script>
@endsection
