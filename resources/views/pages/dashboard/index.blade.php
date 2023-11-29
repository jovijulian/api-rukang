@extends('layouts/content')

@section('title')
  <title>Dashboard</title>
@endsection

@section('content')
  <div class="content">
    {{-- <div class="table-responsive pb-4">
      <div class="d-flex">
        <table id="garuda-segment-table">
          <tbody>
            <tr>
              <td>16</td>
            </tr>
            <tr>
              <td>15</td>
            </tr>
            <tr>
              <td>14</td>
            </tr>
            <tr>
              <td>13</td>
            </tr>
            <tr>
              <td>12</td>
            </tr>
            <tr>
              <td>11</td>
            </tr>
            <tr>
              <td>10</td>
            </tr>
            <tr>
              <td>09</td>
            </tr>
            <tr>
              <td>08</td>
            </tr>
            <tr>
              <td>07</td>
            </tr>
            <tr>
              <td>06</td>
            </tr>
            <tr>
              <td>05</td>
            </tr>
            <tr>
              <td>04</td>
            </tr>
            <tr>
              <td>03</td>
            </tr>
            <tr>
              <td>02</td>
            </tr>
            <tr>
              <td>01</td>
            </tr>
            <tr>
              <td>00</td>
            </tr>
            <tr>
              <td>S/M</td>
            </tr>
          </tbody>
        </table>
        <table id="garuda-table" class="table">
          <tbody>
            <tr>
              <td class="S16 M11"></td>
              <td class="S16 M10"></td>
              <td class="S16 M09"></td>
              <td class="S16 M08"></td>
              <td class="S16 M07"></td>
              <td class="S16 M06"></td>
              <td class="S16 M05"></td>
              <td class="S16 M04"></td>
              <td class="S16 M03"></td>
              <td class="S16 M02"></td>
              <td class="S16 M01"></td>
              <td class="S16 M44"></td>
              <td class="S16 M43"></td>
              <td class="S16 M42"></td>
              <td class="S16 M41"></td>
              <td class="S16 M40"></td>
              <td class="S16 M39"></td>
              <td class="S16 M38"></td>
              <td class="S16 M37"></td>
              <td class="S16 M36"></td>
              <td class="S16 M35"></td>
              <td class="S16 M34"></td>
            </tr>
            <tr>
              <td class="S15 M11"></td>
              <td class="S15 M10"></td>
              <td class="S15 M09"></td>
              <td class="S15 M08"></td>
              <td class="S15 M07"></td>
              <td class="S15 M06"></td>
              <td class="S15 M05"></td>
              <td class="S15 M04"></td>
              <td class="S15 M03"></td>
              <td class="S15 M02"></td>
              <td class="S15 M01"></td>
              <td class="S15 M44"></td>
              <td class="S15 M43"></td>
              <td class="S15 M42"></td>
              <td class="S15 M41"></td>
              <td class="S15 M40"></td>
              <td class="S15 M39"></td>
              <td class="S15 M38"></td>
              <td class="S15 M37"></td>
              <td class="S15 M36"></td>
              <td class="S15 M35"></td>
              <td class="S15 M34"></td>
            </tr>
            <tr>
              <td class="S14 M11"></td>
              <td class="S14 M10"></td>
              <td class="S14 M09"></td>
              <td class="S14 M08"></td>
              <td class="S14 M07"></td>
              <td class="S14 M06"></td>
              <td class="S14 M05"></td>
              <td class="S14 M04"></td>
              <td class="S14 M03"></td>
              <td class="S14 M02"></td>
              <td class="S14 M01"></td>
              <td class="S14 M44"></td>
              <td class="S14 M43"></td>
              <td class="S14 M42"></td>
              <td class="S14 M41"></td>
              <td class="S14 M40"></td>
              <td class="S14 M39"></td>
              <td class="S14 M38"></td>
              <td class="S14 M37"></td>
              <td class="S14 M36"></td>
              <td class="S14 M35"></td>
              <td class="S14 M34"></td>
            </tr>
            <tr>
              <td class="S13 M11"></td>
              <td class="S13 M10"></td>
              <td class="S13 M09"></td>
              <td class="S13 M08"></td>
              <td class="S13 M07"></td>
              <td class="S13 M06"></td>
              <td class="S13 M05"></td>
              <td class="S13 M04"></td>
              <td class="S13 M03"></td>
              <td class="S13 M02"></td>
              <td class="S13 M01"></td>
              <td class="S13 M44"></td>
              <td class="S13 M43"></td>
              <td class="S13 M42"></td>
              <td class="S13 M41"></td>
              <td class="S13 M40"></td>
              <td class="S13 M39"></td>
              <td class="S13 M38"></td>
              <td class="S13 M37"></td>
              <td class="S13 M36"></td>
              <td class="S13 M35"></td>
              <td class="S13 M34"></td>
            </tr>
            <tr>
              <td class="S12 M11"></td>
              <td class="S12 M10"></td>
              <td class="S12 M09"></td>
              <td class="S12 M08"></td>
              <td class="S12 M07"></td>
              <td class="S12 M06"></td>
              <td class="S12 M05"></td>
              <td class="S12 M04"></td>
              <td class="S12 M03"></td>
              <td class="S12 M02"></td>
              <td class="S12 M01"></td>
              <td class="S12 M44"></td>
              <td class="S12 M43"></td>
              <td class="S12 M42"></td>
              <td class="S12 M41"></td>
              <td class="S12 M40"></td>
              <td class="S12 M39"></td>
              <td class="S12 M38"></td>
              <td class="S12 M37"></td>
              <td class="S12 M36"></td>
              <td class="S12 M35"></td>
              <td class="S12 M34"></td>
            </tr>
            <tr>
              <td class="S11 M11"></td>
              <td class="S11 M10"></td>
              <td class="S11 M09"></td>
              <td class="S11 M08"></td>
              <td class="S11 M07"></td>
              <td class="S11 M06"></td>
              <td class="S11 M05"></td>
              <td class="S11 M04"></td>
              <td class="S11 M03"></td>
              <td class="S11 M02"></td>
              <td class="S11 M01"></td>
              <td class="S11 M44"></td>
              <td class="S11 M43"></td>
              <td class="S11 M42"></td>
              <td class="S11 M41"></td>
              <td class="S11 M40"></td>
              <td class="S11 M39"></td>
              <td class="S11 M38"></td>
              <td class="S11 M37"></td>
              <td class="S11 M36"></td>
              <td class="S11 M35"></td>
              <td class="S11 M34"></td>
            </tr>
            <tr>
              <td class="S10 M11"></td>
              <td class="S10 M10"></td>
              <td class="S10 M09"></td>
              <td class="S10 M08"></td>
              <td class="S10 M07"></td>
              <td class="S10 M06"></td>
              <td class="S10 M05"></td>
              <td class="S10 M04"></td>
              <td class="S10 M03"></td>
              <td class="S10 M02"></td>
              <td class="S10 M01"></td>
              <td class="S10 M44"></td>
              <td class="S10 M43"></td>
              <td class="S10 M42"></td>
              <td class="S10 M41"></td>
              <td class="S10 M40"></td>
              <td class="S10 M39"></td>
              <td class="S10 M38"></td>
              <td class="S10 M37"></td>
              <td class="S10 M36"></td>
              <td class="S10 M35"></td>
              <td class="S10 M34"></td>
            </tr>
            <tr>
              <td class="S09 M11"></td>
              <td class="S09 M10"></td>
              <td class="S09 M09"></td>
              <td class="S09 M08"></td>
              <td class="S09 M07"></td>
              <td class="S09 M06"></td>
              <td class="S09 M05"></td>
              <td class="S09 M04"></td>
              <td class="S09 M03"></td>
              <td class="S09 M02"></td>
              <td class="S09 M01"></td>
              <td class="S09 M44"></td>
              <td class="S09 M43"></td>
              <td class="S09 M42"></td>
              <td class="S09 M41"></td>
              <td class="S09 M40"></td>
              <td class="S09 M39"></td>
              <td class="S09 M38"></td>
              <td class="S09 M37"></td>
              <td class="S09 M36"></td>
              <td class="S09 M35"></td>
              <td class="S09 M34"></td>
            </tr>
            <tr>
              <td class="S08 M11"></td>
              <td class="S08 M10"></td>
              <td class="S08 M09"></td>
              <td class="S08 M08"></td>
              <td class="S08 M07"></td>
              <td class="S08 M06"></td>
              <td class="S08 M05"></td>
              <td class="S08 M04"></td>
              <td class="S08 M03"></td>
              <td class="S08 M02"></td>
              <td class="S08 M01"></td>
              <td class="S08 M44"></td>
              <td class="S08 M43"></td>
              <td class="S08 M42"></td>
              <td class="S08 M41"></td>
              <td class="S08 M40"></td>
              <td class="S08 M39"></td>
              <td class="S08 M38"></td>
              <td class="S08 M37"></td>
              <td class="S08 M36"></td>
              <td class="S08 M35"></td>
              <td class="S08 M34"></td>
            </tr>
            <tr>
              <td class="S07 M11"></td>
              <td class="S07 M10"></td>
              <td class="S07 M09"></td>
              <td class="S07 M08"></td>
              <td class="S07 M07"></td>
              <td class="S07 M06"></td>
              <td class="S07 M05"></td>
              <td class="S07 M04"></td>
              <td class="S07 M03"></td>
              <td class="S07 M02"></td>
              <td class="S07 M01"></td>
              <td class="S07 M44"></td>
              <td class="S07 M43"></td>
              <td class="S07 M42"></td>
              <td class="S07 M41"></td>
              <td class="S07 M40"></td>
              <td class="S07 M39"></td>
              <td class="S07 M38"></td>
              <td class="S07 M37"></td>
              <td class="S07 M36"></td>
              <td class="S07 M35"></td>
              <td class="S07 M34"></td>
            </tr>
            <tr>
              <td class="S06 M11"></td>
              <td class="S06 M10"></td>
              <td class="S06 M09"></td>
              <td class="S06 M08"></td>
              <td class="S06 M07"></td>
              <td class="S06 M06"></td>
              <td class="S06 M05"></td>
              <td class="S06 M04"></td>
              <td class="S06 M03"></td>
              <td class="S06 M02"></td>
              <td class="S06 M01"></td>
              <td class="S06 M44"></td>
              <td class="S06 M43"></td>
              <td class="S06 M42"></td>
              <td class="S06 M41"></td>
              <td class="S06 M40"></td>
              <td class="S06 M39"></td>
              <td class="S06 M38"></td>
              <td class="S06 M37"></td>
              <td class="S06 M36"></td>
              <td class="S06 M35"></td>
              <td class="S06 M34"></td>
            </tr>
            <tr>
              <td class="S05 M11"></td>
              <td class="S05 M10"></td>
              <td class="S05 M09"></td>
              <td class="S05 M08"></td>
              <td class="S05 M07"></td>
              <td class="S05 M06"></td>
              <td class="S05 M05"></td>
              <td class="S05 M04"></td>
              <td class="S05 M03"></td>
              <td class="S05 M02"></td>
              <td class="S05 M01"></td>
              <td class="S05 M44"></td>
              <td class="S05 M43"></td>
              <td class="S05 M42"></td>
              <td class="S05 M41"></td>
              <td class="S05 M40"></td>
              <td class="S05 M39"></td>
              <td class="S05 M38"></td>
              <td class="S05 M37"></td>
              <td class="S05 M36"></td>
              <td class="S05 M35"></td>
              <td class="S05 M34"></td>
            </tr>
            <tr>
              <td class="S04 M11"></td>
              <td class="S04 M10"></td>
              <td class="S04 M09"></td>
              <td class="S04 M08"></td>
              <td class="S04 M07"></td>
              <td class="S04 M06"></td>
              <td class="S04 M05"></td>
              <td class="S04 M04"></td>
              <td class="S04 M03"></td>
              <td class="S04 M02"></td>
              <td class="S04 M01"></td>
              <td class="S04 M44"></td>
              <td class="S04 M43"></td>
              <td class="S04 M42"></td>
              <td class="S04 M41"></td>
              <td class="S04 M40"></td>
              <td class="S04 M39"></td>
              <td class="S04 M38"></td>
              <td class="S04 M37"></td>
              <td class="S04 M36"></td>
              <td class="S04 M35"></td>
              <td class="S04 M34"></td>
            </tr>
            <tr>
              <td class="S03 M11"></td>
              <td class="S03 M10"></td>
              <td class="S03 M09"></td>
              <td class="S03 M08"></td>
              <td class="S03 M07"></td>
              <td class="S03 M06"></td>
              <td class="S03 M05"></td>
              <td class="S03 M04"></td>
              <td class="S03 M03"></td>
              <td class="S03 M02"></td>
              <td class="S03 M01"></td>
              <td class="S03 M44"></td>
              <td class="S03 M43"></td>
              <td class="S03 M42"></td>
              <td class="S03 M41"></td>
              <td class="S03 M40"></td>
              <td class="S03 M39"></td>
              <td class="S03 M38"></td>
              <td class="S03 M37"></td>
              <td class="S03 M36"></td>
              <td class="S03 M35"></td>
              <td class="S03 M34"></td>
            </tr>
            <tr>
              <td class="S02 M11"></td>
              <td class="S02 M10"></td>
              <td class="S02 M09"></td>
              <td class="S02 M08"></td>
              <td class="S02 M07"></td>
              <td class="S02 M06"></td>
              <td class="S02 M05"></td>
              <td class="S02 M04"></td>
              <td class="S02 M03"></td>
              <td class="S02 M02"></td>
              <td class="S02 M01"></td>
              <td class="S02 M44"></td>
              <td class="S02 M43"></td>
              <td class="S02 M42"></td>
              <td class="S02 M41"></td>
              <td class="S02 M40"></td>
              <td class="S02 M39"></td>
              <td class="S02 M38"></td>
              <td class="S02 M37"></td>
              <td class="S02 M36"></td>
              <td class="S02 M35"></td>
              <td class="S02 M34"></td>
            </tr>
            <tr>
              <td class="S01 M11"></td>
              <td class="S01 M10"></td>
              <td class="S01 M09"></td>
              <td class="S01 M08"></td>
              <td class="S01 M07"></td>
              <td class="S01 M06"></td>
              <td class="S01 M05"></td>
              <td class="S01 M04"></td>
              <td class="S01 M03"></td>
              <td class="S01 M02"></td>
              <td class="S01 M01"></td>
              <td class="S01 M44"></td>
              <td class="S01 M43"></td>
              <td class="S01 M42"></td>
              <td class="S01 M41"></td>
              <td class="S01 M40"></td>
              <td class="S01 M39"></td>
              <td class="S01 M38"></td>
              <td class="S01 M37"></td>
              <td class="S01 M36"></td>
              <td class="S01 M35"></td>
              <td class="S01 M34"></td>
            </tr>
            <tr>
              <td class="S00 M11"></td>
              <td class="S00 M10"></td>
              <td class="S00 M09"></td>
              <td class="S00 M08"></td>
              <td class="S00 M07"></td>
              <td class="S00 M06"></td>
              <td class="S00 M05"></td>
              <td class="S00 M04"></td>
              <td class="S00 M03"></td>
              <td class="S00 M02"></td>
              <td class="S00 M01"></td>
              <td class="S00 M44"></td>
              <td class="S00 M43"></td>
              <td class="S00 M42"></td>
              <td class="S00 M41"></td>
              <td class="S00 M40"></td>
              <td class="S00 M39"></td>
              <td class="S00 M38"></td>
              <td class="S00 M37"></td>
              <td class="S00 M36"></td>
              <td class="S00 M35"></td>
              <td class="S00 M34"></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>11</td>
              <td>10</td>
              <td>09</td>
              <td>08</td>
              <td>07</td>
              <td>06</td>
              <td>05</td>
              <td>04</td>
              <td>03</td>
              <td>02</td>
              <td>01</td>
              <td>44</td>
              <td>43</td>
              <td>42</td>
              <td>41</td>
              <td>40</td>
              <td>39</td>
              <td>38</td>
              <td>37</td>
              <td>36</td>
              <td>35</td>
              <td>34</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div> --}}

    @include('partials.garuda-table')
    

    <div class="mt-4">
      <div class="row" id="status-bilah"></div>
      <div class="row" id="status-product"></div>
    </div>

    <div class="card">
      <div class="card-header pb-0">
        <div class="card-title">Data Agregat Berdasarkan Status</div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-nowrap" id="table-agregat-status">
            <thead class="text-center">
              <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Nama Status</th>
                <th colspan="2">Kategori</th>
                <th rowspan="2">Total</th>
              </tr>
              <tr>
                <th>Kulit (Kuningan)</th>
                <th>Rangka (Perforated)</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header pb-0">
        <div class="card-title">Data Agregat Berdasarkan Segmen</div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-nowrap" id="table-agregat-segment">
            <thead class="text-center">
              <tr>
                <th rowspan="2">Segmen</th>
                <th rowspan="2">Kategori</th>
                <th colspan="5">Status</th>
                <th rowspan="2">Total</th>
              </tr>
              <tr>
                <th>Selesai Produksi</th>
                <th>Pengiriman</th>
                <th>Diterima</th>
                <th>Pemasangan</th>
                <th>Disetujui PP</th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot></tfoot>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header row">
        <div id="title-chart" class="card-title col-lg-4">Grafik Harian Selesai Produksi Bilah</div>
        <div class="form-group row col-lg-3 ms-lg-auto">
          <div class="col-lg-12">
            <input class="form-control" type="month" id="month-year">
          </div>
        </div>
        <div class="form-group row col-lg-3">
          <div class="col-lg-12">
            <select id="status-filter" class="form-control select" style="padding: 8px">
              <option value="17">Selesai Produksi</option>
              <option value="20">Pengiriman</option>
              <option value="26">Pemasangan (Erection)</option>
            </select>
          </div>
        </div>
        <div class="row col-lg-2">
          <div class="col-lg-12">
            <button id="btn-filter" class="btn btn-primary p-2 text-white w-100">Filter</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="dashboard-chart">
          <canvas id="production-chart" height="500"></canvas>
        </div>
      </div>
    </div>
  </div>

  @include('partials.dashboard-chart')

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

    let labelChart = []
    let dataChart = []


    $(document).ready(function() {
      // STATUS
      const elementStatus = (title, total, totalKulit, totalRangka) => {
        const countTotal = total + .00
        const countKulit = totalKulit + .00
        const countRangka = totalRangka + .00
        return `
          <div class="col-lg-3 col-sm-6 col-12">
            <div class="bg-white p-4 card">
              <h2 style="font-weight: 700"><span class="counters" data-count="${countTotal}">${total}</span></h2>
              <h6 style="font-weight: 500">${title}</h6>
              <div class="row mt-2 text-center">
                <div class="col-6 border py-2">
                  <p style="font-weight: 600">Kulit</p>
                </div>
                <div class="col-6 border py-2">
                  <p style="font-weight: 600">Rangka</p>
                </div>
                <div class="col-6 border py-2">
                  <p><span class="counters" data-count="${countKulit}">${totalKulit}</span></p>
                </div>
                <div class="col-6 border py-2">
                  <p><span class="counters" data-count="${countRangka}">${totalRangka}</span></p>
                </div>
              </div>
            </div>
          </div>
        `
      }

      axios.get("{{ url('api/v1/dashboard/index-status') }}", config)
        .then(res => {
          const data = res.data
          const dataProduct = data.data_product
          const dataProductPerStatus = data.data_product_per_status

          dataProduct.map(data => {
            $('#status-bilah').append(elementStatus(data.title, data.total, data.kategori_kulit, data.kategori_rangka))
          })

          dataProductPerStatus.map(data => {
            let title = data.title.split(' ').slice(1).join(' ')
            title = 'Total ' + title

            $('#status-product').append(elementStatus(title, data.total, data.kategori_kulit, data.kategori_rangka))
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

      // TABLE AGREGAT
      axios.get("{{ url('api/v1/dashboard/index-agregat-status') }}", config)
        .then(res => {
          const datas = res.data
          const dataPerStatus = res.data.data_product_per_status

          dataPerStatus.map((data, i) => {
            $('#table-agregat-status tbody').append(`
              <tr>
                <td class="text-center">${i + 1}</td>
                <td>${data.nama_status}</td>
                <td class="text-center">${data.kategori_kulit}</td>
                <td class="text-center">${data.kategori_rangka}</td>
                <td class="text-center">${data.total}</td>
              </tr>
            `)
          })

          $('#table-agregat-status tfoot').append(`
            <tr style="font-weight: 700">
              <td class="text-center"></td>
              <td class="text-center">Total</td>
              <td class="text-center">${datas.total_kategori_kulit}</td>
              <td class="text-center">${datas.total_kategori_rangka}</td>
              <td class="text-center">${datas.total_product}</td>
            </tr>
          `)
        })
        .catch(err => {
          console.log(err)
        })

      axios.get("{{ url('api/v1/dashboard/index-agregat-segment') }}", config)
        .then(res => {
          const datas = res.data
          const dataPerSegment = res.data.data_product_per_segment

          dataPerSegment.map(data => {
            $('#table-agregat-segment tbody').append(`
              <tr>
                <td rowspan="2" class="text-center">${data.nama_segment}</td>
                <td>Kulit</td>
                <td class="text-center">${data.data_per_status[0].kategori_kulit_per_status}</td>
                <td class="text-center">${data.data_per_status[1].kategori_kulit_per_status}</td>
                <td class="text-center">${data.data_per_status[2].kategori_kulit_per_status}</td>
                <td class="text-center">${data.data_per_status[3].kategori_kulit_per_status}</td>
                <td class="text-center">${data.data_per_status[4].kategori_kulit_per_status}</td>
                <td class="text-center">${data.total_kategori_kulit}</td>
              </tr>
              <tr>
                <td>Rangka</td>
                <td class="text-center">${data.data_per_status[0].kategori_rangka_per_status}</td>
                <td class="text-center">${data.data_per_status[1].kategori_rangka_per_status}</td>
                <td class="text-center">${data.data_per_status[2].kategori_rangka_per_status}</td>
                <td class="text-center">${data.data_per_status[3].kategori_rangka_per_status}</td>
                <td class="text-center">${data.data_per_status[4].kategori_rangka_per_status}</td>
                <td class="text-center">${data.total_kategori_rangka}</td>
              </tr>
            `)
          })

          $('#table-agregat-segment tfoot').append(`
            <tr style="font-weight: 700">
              <td rowspan="2" class="text-center">Total</td>
              <td>Kulit</td>
              <td class="text-center">${datas.total_kategori_kulit_per_status1}</td>
              <td class="text-center">${datas.total_kategori_kulit_per_status2}</td>
              <td class="text-center">${datas.total_kategori_kulit_per_status3}</td>
              <td class="text-center">${datas.total_kategori_kulit_per_status4}</td>
              <td class="text-center">${datas.total_kategori_kulit_per_status5}</td>
              <td class="text-center">${datas.total_kategori_kulit}</td>
            </tr>
            <tr style="font-weight: 700">
              <td>Rangka</td>
              <td class="text-center">${datas.total_kategori_rangka_per_status1}</td>
              <td class="text-center">${datas.total_kategori_rangka_per_status2}</td>
              <td class="text-center">${datas.total_kategori_rangka_per_status3}</td>
              <td class="text-center">${datas.total_kategori_rangka_per_status4}</td>
              <td class="text-center">${datas.total_kategori_rangka_per_status5}</td>
              <td class="text-center">${datas.total_kategori_rangka}</td>
            </tr>
          `)

        })
        .catch(err => {
          console.log(err)
        })
  
    })
  </script>
@endsection
