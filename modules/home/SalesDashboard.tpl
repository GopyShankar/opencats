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
                    <div class="noteUnsizedSpan">Shortlisted status monthly report</div>
                        <div id="chart6"></div>
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

        var chart = new ApexCharts(document.querySelector("#chart10"), options);
        chart.render();
        /*------Multiple Y axis Chart-------*/
        var options = {
          series: [{
          name: 'New Profiles',
          type: 'column',
          data: [14, 15, 20, 05, 03, 20,44,50,30,12],
          color: '#00E396'
        }, {
          
          name: 'Recycled Profiles',
          type: 'column',
          data: [26, 16, 10, 07, 43, 41,31,20,10,10],
          color: '#FF0000'
        }, {
          name: 'Shortlisted',
          type: 'line',
          data: [38, 99, 30, 91, 14, 91,80,70,80,100]
        }],
          chart: {
          height: 300,
          type: 'line',
          stacked: false
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          width: [1, 1, 4]
        },
        
        xaxis: {
          categories:  ['07/22/2021','07/23/2021', '07/24/2021', '07/25/2021', '07/26/2021', '07/27/2021', '07/28/2021','07/29/2021','07/30/2021','07/31/2021'],
          type: 'datetime'
        },
        yaxis: [
          {
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#00E396'
            },
            labels: {
              style: {
                colors: '#00E396',
              }
            },
            title: {
              text: "New Profiles",
              style: {
                color: '#00E396',
              }
            },
            tooltip: {
              enabled: true
            }
          },
          {
            seriesName: 'New Profiles',
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#FF0000'
            },
            labels: {
              style: {
                colors: '#FF0000',
              }
            },
            title: {
              text: "Recycled Profiles",
              style: {
                color: '#FF0000',
              }
            },
          },
          {
            seriesName: 'Shortlisted',
            opposite: true,
            axisTicks: {
              show: true,
            },
            axisBorder: {
              show: true,
              color: '#FEB019'
            },
            labels: {
              style: {
                colors: '#FEB019',
              },
            },
            title: {
              text: "Shortlisted",
              style: {
                color: '#FEB019',
              }
            }
          },
        ],
        tooltip: {
          fixed: {
            enabled: true,
            position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
            offsetY: 30,
            offsetX: 60
          },
        },
        legend: {
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();
        
        /*---Donut Chart-----*/
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
        
       /*---Pie Chart-----*/
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

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
        
        /*---Line with Data lables -----*/
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
        
        /*----Radial Bar chart multiple----*/
        var options = {
          series: [84,35,32,30,05],
          chart: {
          height: 350,
          type: 'radialBar',
        },
        plotOptions: {
          radialBar: {
            dataLabels: {
              name: {
                fontSize: '22px',
              },
              value: {
                fontSize: '16px',
              },
              total: {
                show: true,
                label: 'Total',
                formatter: function (w) {
                  // By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
                  return 186
                }
              }
            }
          }
        },
        labels: ['PipeLine', 'Confirmed', 'Released', 'On-Board', 'Rejected'],
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();
        
        /*-----100 % Stacket Bar Chart-----*/
        var options = {
          series: [{
          name: 'L1',
          data: [44, 55, 41, 37, 22, 43, 21]
        }, {
          name: 'L2',
          data: [53, 32, 33, 52, 13, 43, 32]
        }, {
          name: 'CE',
          data: [12, 17, 11, 9, 15, 11, 20]
        }, {
          name: 'BGV',
          data: [9, 7, 5, 8, 6, 9, 4]
        }, {
          name: 'Pending',
          data: [25, 12, 19, 32, 25, 24, 10]
        },{
          name: 'Pipeline',
          data: [44, 55, 41, 37, 22, 43, 21]
        }, {
          name: 'Confirmed',
          data: [53, 32, 33, 52, 13, 43, 32]
        }, {
          name: 'Released',
          data: [12, 17, 11, 9, 15, 11, 20]
        }, {
          name: 'On-Board',
          data: [9, 7, 5, 8, 6, 9, 4]
        }, {
          name: 'Rejected',
          data: [25, 12, 19, 32, 25, 24, 10]
        }
        ],
          chart: {
          type: 'bar',
          height: 350,
          stacked: true,
          stackType: '100%'
        },
        plotOptions: {
          bar: {
            horizontal: true,
          },
        },
        stroke: {
          width: 1,
          colors: ['#fff']
        },
        
        xaxis: {
          categories: ['Aug', 'Jul', 'Jun','May','Apr','Mar','Feb','Jan'],
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return val
            }
          }
        },
        fill: {
          opacity: 1
        
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left',
          offsetX: 40
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart6"), options);
        chart.render();
      
    });
    </script>
<?php TemplateUtility::printFooter(); ?>
