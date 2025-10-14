<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Laravel Highcharts Demo</title>
</head>
<body>
    <h1>Highcharts in Laravel Example</h1>
    <div id="container"></div>
</body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    var userData = <?php echo json_encode($data)?>;
    Highcharts.chart('container', {
        title: {
            text: 'New User Growth, 2020'
        },
        subtitle: {
            text: 'Source: positronx.io'
        },
        xAxis: {
            categories: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
                'October', 'November', 'December'
            ]
        },
        yAxis: {
            title: {
                text: 'Number of New Users'
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            name: 'New Users',
            data: [
        {
          name: 'Chrome',
          y: 63.06,
          drilldown: 'Chrome'
        },
        {
          name: 'Safari',
          y: 19.84,
          drilldown: 'Safari'
        },
        {
          name: 'Firefox',
          y: 4.18,
          drilldown: 'Firefox'
        },
        {
          name: 'Edge',
          y: 4.12,
          drilldown: 'Edge'
        },
        {
          name: 'Opera',
          y: 2.33,
          drilldown: 'Opera'
        },
        {
          name: 'Internet Explorer',
          y: 0.45,
          drilldown: 'Internet Explorer'
        },
        {
          name: 'Other',
          y: 1.582,
          drilldown: null
        }
      ]
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>
</html>

