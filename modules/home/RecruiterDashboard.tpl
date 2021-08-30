<?php /* $Id: Home.tpl 3563 2007-11-12 07:41:54Z will $ */ ?>
<?php TemplateUtility::printHeader('Home', array('js/jquery-1.3.2.min.js','js/sweetTitles.js', 'js/dataGrid.js', 'js/dataGridFilters.js', 'js/home.js','js/apexcharts/apexcharts.min.js',js/datatable/datatables.min.js)); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main" class="home">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents" style="padding-top: 10px;">

            <table>
                <tr>
                    <td align="left" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Candidates Selection Details Last 10 Days</div>
                        <div id="chart1"></div>
                    </td>

                    <td align="center" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Team Lead Selected Candidates Progress Status</div>
                        <div id="chart3"></div>
                    </td>

                </tr>
            </table>

            <table>
                <tr>
                    
                    <td align="center" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Selected Candidates Response Status</div>
                        <div id="chart2"></div>
                    </td>

                    <td align="center" valign="top" style="text-align: left; width: 50%; height: 240px;">
                        <div class="noteUnsizedSpan">Hiring Overview</div>
                        <div id="chart5"></div>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    
                    <td align="center" valign="top" style="text-align: left; height:350px;">
                    <div class="noteUnsizedSpan">Datatable Section</div>
                        
                    </td>

                </tr>
            </table>
        </div>
    </div>
    <script>
    $(document).ready(function () {
    var options = {
          series: [{
          name: 'Invited',
          data: [78, 130, 60, 103, 60, 152,155,140,130,122]
        }, {
          name: 'Shortlisted',
          data: [38, 99, 30, 91, 14, 91,80,70,80,100]
        }, {
          name: 'Active',
          data: [14, 15, 20, 05, 03, 20,44,50,30,12]
        },
        {
          name: 'Rejected',
          data: [26, 16, 10, 07, 43, 41,31,20,10,10]
          
        }],
          chart: {
          type: 'bar',
          height: 300,
          width:900
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '40',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width:15,
          colors: ['transparent']
        },
        xaxis: {
        	title: {
            text: 'Date'
          },
          type: 'datetime',
          categories: ['07/22/2021 GMT','07/23/2021 GMT', '07/24/2021 GMT', '07/25/2021 GMT', '07/26/2021 GMT', '07/27/2021 GMT', '07/28/2021 GMT','07/29/2021 GMT','07/30/2021 GMT','07/31/2021 GMT'],
        },
        yaxis: {
          title: {
            text: 'Count'
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
        
        var options = {
          series: [105, 98, 80, 62, 25],
          chart: {
          width: 380,
          type: 'pie',
        },
        labels: ['L1', 'L2', 'CE', 'BGV', 'Pending'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
        
        
      
        var options = {
          series: [84,35,32,30,05],
          chart: {
          type: 'donut',
        },
        labels: ['PipeLine', 'Confirmed', 'Released', 'On-Board', 'Rejected'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();
        
        
         var options = {
          series: [
          {
            name: "Invited - 2021",
            data: [28, 29, 33, 36, 32, 32, 33,15]
          },
          {
            name: "Selected - 2021",
            data: [12, 11, 14, 18, 17, 13, 13,06]
          }
        ],
          chart: {
          height: 350,
          type: 'line',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2
          },
          toolbar: {
            show: false
          }
        },
        colors: ['#77B6EA', '#545454'],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: 'smooth'
        },
        title: {
          text: 'Average Invited & Selected Candidates',
          align: 'left'
        },
        grid: {
          borderColor: '#e7e7e7',
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug'],
          title: {
            text: 'Month'
          }
        },
        yaxis: {
          title: {
            text: 'Candidates Count'
          },
          min: 5,
          max: 40
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart5"), options);
        chart.render();
      
    });
    </script>
<?php TemplateUtility::printFooter(); ?>
