{{-- Chart JS --}}
<script src="{{ url('assets/plugins/chartjs/chart.min.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#month-year').val(new Date().toISOString().slice(0,7))

    const defaultParam = 'status_id=17'
    getChartData(defaultParam)

    const ctx = document.getElementById('production-chart')
    let chart
    let labelChart = []
    let dataChart = []


    function getChartData(param) {
       // CHART
      axios.get(`{{ url('api/v1/dashboard/index-grafik?${param}') }}`, config)
        .then(res => {
          const datas = res.data.data
          const arrTitle = res.data.status_name.split(' ')
          arrTitle.shift()
          const title = arrTitle.join(' ')

          $('#title-chart').text(`Grafik Harian ${title} Bilah`)

          labelChart = []
          dataChart = []

          datas.map((data, i) => {
            const day = new Date(data.daily).getDate()
            const month = new Date(data.daily).toLocaleString('id-ID', { month: 'short' })
            labelChart.push(day + ' ' + month)
            dataChart.push(data.total_data)
          })

          createChart(labelChart, dataChart, title)

        })
        .catch(err => {
          console.log(err)
        })
    }

    function createChart(labels, data, title) {
      chart =  new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: `Grafik Harian ${title} Bilah`,
            data: data,
            backgroundColor: 'rgb(54, 162, 235)'
          }]
        },
        options: {
          maintainAspectRatio: false,
          responsive: true,
          legend: {
            display: false,
            labels: {
              display: true
            }
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
                fontSize: 10,
                max: 80
              }
            }],
            xAxes: [{
              barPercentage: 0.6,
              ticks: {
                beginAtZero: true,
                fontSize: 11
              }
            }]
          }
        }
      })
    }


    const btnFilter = $('#btn-filter').on('click', () => {
      const date = $('#month-year').val().split("-")
      const year = date[0]
      const month = date[1]
      const status = $('#status-filter').val()
      const param = `status_id=${status}&bulan=${month}&tahun=${year}`

      chart.destroy()
      
      getChartData(param)
    })

  })
</script>