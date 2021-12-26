<?php /* $Id: Home.tpl 3563 2007-11-12 07:41:54Z will $ */ ?>
<?php TemplateUtility::printHeader('Home', array('js/jquery-1.3.2.min.js','js/sweetTitles.js', 'js/dataGrid.js', 'js/dataGridFilters.js', 'js/home.js','js/apexcharts/apexcharts.min.js','js/datatable/datatables.min.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active); ?>
    <div id="main" class="home">
        
        <div id="contents" style="padding-top: 10px;">
            <table width="100%">
                <tr>
                    <td align="right">
                        <?php echo($this->quickLinks); ?>
                    </td>
                </tr>
            </table>
            
            <table>
                <tr>
                    <td align="left" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Candidates Selection Details</div>
                        <div id="chart1"></div>
                    </td>

                    <td align="center" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Candidates Selection Details</div>
                        <div id="chart2"></div>
                    </td>

                </tr>
            </table>
            <table>
                <tr>
                    <td align="left" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Team Lead Selected Candidates Progress Status</div>
                        <div id="chart3"></div>
                    </td>

                    <td align="center" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Selected Candidates Response Status</div>
                        <div id="chart4"></div>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td align="center" valign="top" style="text-align: left; width: 50%; height: 240px;">
                        <div class="noteUnsizedSpan">Hiring Overview</div>
                        <div id="chart5"></div>
                    </td>
                </tr>
            </table>
            
        </div>
    </div>
    <script>
    $(document).ready(function () {
        
        /**** Chart 1 start ****/

        var chart1 = '<?=json_encode($this->chart1)?>';
        
        var chart1 = JSON.parse(chart1);
        var dateList = [], value1 = [], value2 = [], value3 = [];
        $.each(chart1,function(key,value){
            dateList.push(value.date_list);
            value1.push(value.counts);
            value2.push(value.dataCount);
            value3.push(value.statusCount);
        });

        var options = {
            series: [{
                name: 'New Profiles',
                data: value1
            }, {
                name: 'Recycled Profiles',
                data: value2
            }, {
                name: 'Shortlisted',
                data: value3
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: dateList,
            },
            yaxis: {
                title: {
                    text: 'Totals'
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
            },
            noData: {
                text: 'No data found',
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: 'red',
                    fontSize: '14px',
                    fontFamily: undefined
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();

        /**** Chart 1 end ****/

        /**** Chart 2 start ****/

        var chart2 = '<?=json_encode($this->chart2)?>';
        
        var chart2 = JSON.parse(chart2);
        var dateList = [], value1 = [], value2 = [], value3 = [], value4 = [], value5 = [], value6 = [];
        $.each(chart2,function(key,value){
            dateList.push(value.date_list);
            value1.push(value.val525);
            value2.push(value.val550);
            value3.push(value.val560);
            value4.push(value.val570);
            value5.push(value.val580);
            value6.push(value.val590);
        });

        var options = {
            series: [{
                name: 'L1',
                data: value1
            }, {
                name: 'L2',
                data: value2
            }, {
                name: 'CE',
                data: value3
            }, {
                name: 'CE Sent',
                data: value4
            }, {
                name: 'CE Not Available',
                data: value5
            }, {
                name: 'CE Pending',
                data: value6
            }],
              chart: {
                height: 350,
                type: 'area'
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth'
            },
            xaxis: {
              // type: 'date',
              categories: dateList
            },
            tooltip: {
              x: {
                format: 'dd/MM/yy'
              },
            },
            noData: {
                text: 'No data found',
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: 'red',
                    fontSize: '14px',
                    fontFamily: undefined
                }
            },
            colors: ['#6495ED','#FFBF00', '#FF7F50', '#DE3163','#9FE2BF','#40E0D0']
        };

        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();

        /**** Chart 2 end ****/


        /**** Chart 3 start ****/

        var chart3 = '<?=json_encode($this->chart3)?>';
        
        var chart3 = JSON.parse(chart3);
        var dateList = [], value1 = [], value2 = [], value3 = [], value4 = [], value5 = [], value6 = [];
        $.each(chart3,function(key,value){
            dateList.push(value.date_list);
            value1.push(value.val555);
            value2.push(value.val675);
            value3.push(value.val940);
            value4.push(value.val945);
            value5.push(value.val955);
            value6.push(value.val975);
        });

        var options = {
            series: [{
                name: 'Selected',
                data: value1
            }, {
                name: 'Pending',
                data: value2
            }, {
                name: 'Responded/Pipeline',
                data: value3
            }, {
                name: 'No show',
                data: value4
            }, {
                name: 'Confirmed',
                data: value5
            }, {
                name: 'Not Available',
                data: value6
            }],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'straight'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: dateList,
            },
            colors: ['#6495ED','#FFBF00', '#FF7F50', '#DE3163','#9FE2BF','#40E0D0'],
            noData: {
                text: 'No data found',
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: 'red',
                    fontSize: '14px',
                    fontFamily: undefined
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();

        /**** Chart 3 end ****/


        /**** Chart 4 start ****/

        var chart4 = '<?=json_encode($this->chart4)?>';
        
        var chart4 = JSON.parse(chart4);
        var dateList = [], value1 = [], value2 = [], value3 = [], value4 = [], value5 = [];
        $.each(chart4,function(key,value){
            dateList.push(value.date_list);
            value1.push(value.val910);
            value2.push(value.val920);
            value3.push(value.val930);
            value4.push(value.val980);
            value5.push(value.val990);
        });

        var options = {
            series: [{
                name: 'BGV Sent',
                data: value1
            }, {
                name: 'BGV Pending',
                data: value2
            }, {
                name: 'BGV Cleared',
                data: value3
            }, {
                name: 'On-Board',
                data: value4
            }, {
                name: 'Released',
                data: value5
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: dateList,
            },
            yaxis: {
                title: {
                    text: 'Totals'
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
            },
            noData: {
                text: 'No data found',
                align: 'center',
                verticalAlign: 'middle',
                offsetX: 0,
                offsetY: 0,
                style: {
                    color: 'red',
                    fontSize: '14px',
                    fontFamily: undefined
                }
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();

        /**** Chart 4 end ****/

        /**** Chart 5 start ****/

        var invitedList = '<?=json_encode($this->invitedList)?>';
        
        var invitedList = JSON.parse(invitedList);
        var invitedListNew = new Array();
        
        $.each(invitedList,function(key,value){
            invitedListNew.push(parseInt(value));
        })

        var selectedList = '<?=json_encode($this->selectedList)?>';
        
        var selectedList = JSON.parse(selectedList);
        var selectedListNew = new Array();
        
        $.each(selectedList,function(key,value){
            selectedListNew.push(parseInt(value));
        })
       
        
        /*---Line with Data lables -----*/
         var options = {
          series: [
          {
            name: "Invited - 2021",
            data: invitedListNew
          },
          {
            name: "Selected - 2021",
            data: selectedListNew
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
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
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

        /**** Chart 5 end ****/
      
    });
    </script>
<?php TemplateUtility::printFooter(); ?>
