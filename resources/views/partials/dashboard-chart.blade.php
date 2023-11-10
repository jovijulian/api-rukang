{{-- Chart JS --}}
<script src="{{ url('assets/plugins/chartjs/chart.min.js') }}"></script>
<script>
  $(document).ready(function() {
    // CHART
    axios.get("{{ url('api/v1/dashboard/index-grafik?status_id=17&group_by=0') }}", config)
      .then(res => {
        const datas = res.data.data

        datas.map((data, i) => {
          const day = new Date(data.daily).getDate()
          const month = new Date(data.daily).toLocaleString('id-ID', { month: 'short' })
          labelChart.push(day + ' ' + month)
          dataChart.push(data.total_data)
        })

        createChart(labelChart, dataChart)

      })
      .catch(err => {
        console.log(err)
      })

      const createChart = (labels, data) => {
        const canvas = document.getElementById('production-chart')
        const ctx = canvas.getContext('2d')

        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Grafik Harian Produksi Bilah',
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
        });
      }
  })
</script>