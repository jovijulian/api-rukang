@extends('layouts/content')

@section('title')
  <title>Dashboard</title>
@endsection

@section('content')
  <div class="content">
    <div class="row" id="status-product">
      {{-- <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget">
          <div class="dash-widgetcontent">
            <h3 style="font-weight: 700"><span class="counters" data-count="30.00"></span></h3>
            <h6>Total Produk Dikirim</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget">
          <div class="dash-widgetcontent">
            <h3 style="font-weight: 700"><span class="counters" data-count="60.00"></span></h3>
            <h6>Total Produk Selesai Diproduksi</h6>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-12">
        <div class="dash-widget">
          <div class="dash-widgetcontent">
            <h3 style="font-weight: 700"><span class="counters" data-count="15.00"></span></h3>
            <h6>Total Produk Dikirim</h6>
          </div>
        </div>
      </div> --}}
      {{-- <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count">
          <div class="dash-counts">
            <h4>100</h4>
            <h5>Customers</h5>
          </div>
          <div class="dash-imgs">
            <i data-feather="user"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das1">
          <div class="dash-counts">
            <h4>100</h4>
            <h5>Suppliers</h5>
          </div>
          <div class="dash-imgs">
            <i data-feather="user-check"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das2">
          <div class="dash-counts">
            <h4>100</h4>
            <h5>Purchase Invoice</h5>
          </div>
          <div class="dash-imgs">
            <i data-feather="file-text"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das3">
          <div class="dash-counts">
            <h4>105</h4>
            <h5>Sales Invoice</h5>
          </div>
          <div class="dash-imgs">
            <i data-feather="file"></i>
          </div>
        </div>
      </div> --}}
    </div>
  </div>

  <script>
    const currentUser = JSON.parse(localStorage.getItem('current_user'))
    const tokenType = localStorage.getItem('token_type')
    const accessToken = localStorage.getItem('access_token')

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
      axios.get("{{ url('api/v1/dashboard/index-status') }}", config)
        .then(res => {
          const datas = res.data.data


          datas.map(data => {
            const count = data.total + .00
            const el = `
              <div class="col-lg-3 col-sm-6 col-12">
                <div class="dash-widget">
                  <div class="dash-widgetcontent">
                    <h3 style="font-weight: 700"><span class="counters" data-count="${count}">${data.total}</span></h3>
                    <h6>Total ${data.title}</h6>
                  </div>
                </div>
              </div>
            `

            $('#status-product').append(el)
          })

          $('.counters').each(function() {
            var $this = $(this),
              countTo = parseFloat($this.attr('data-count'))

            $({
              countNum: 0
            }).animate({
              countNum: countTo
            }, {
              duration: 2000,
              easing: 'linear',
              step: function() {
                $this.text(Math.floor(this.countNum))
              },
              complete: function() {
                $this.text(Math.floor(countTo))
              }
            })
          })

        })
        .catch(err => {
          console.log(err)
        })
    })
  </script>
@endsection
