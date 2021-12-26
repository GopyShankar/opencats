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

                    <td align="left" valign="top" style="text-align: left; height:350px;">
                        <div class="noteUnsizedSpan">Candidates Selection Details</div>
                        <div id="chart2"></div>
                    </td>

                    <td align="center" valign="top" style="text-align: left; height:350px;">
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
                    

                </tr>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            /*----chart 1 start ----*/
            var chart1 = '<?=json_encode($this->chart1)?>';
            var chart1 = JSON.parse(chart1);

            var chart1New = new Array();
            $.each(chart1,function(key,value){
                chart1New.push(parseInt(value));
            })
            

            var options = {
                series: chart1New,
                chart: {
                    width: 400,
                    type: 'donut',
                },
                labels: ['Profiles Sent', 'New Profiles ', 'Recycled Profiles'],
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 90,
                        offsetY: 10
                    }
                },
                grid: {
                    padding: {
                        bottom: -80
                    }
                },
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
                }],
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
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart1"), options);
            chart.render();

            /*----chart1 end ----*/
            /*----chart2 start ----*/

            var chart2 = '<?=json_encode($this->chart2)?>';
            var chart2 = JSON.parse(chart2);

            var chart2New = new Array();
            $.each(chart2,function(key,value){
                chart2New.push(parseInt(value));
            })

            var options = {
                series: chart2New,
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: ['L1', 'L2', 'CE', 'CE Sent', 'CE Not Available', 'CE Pending'],
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
                }],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
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
            };

            var chart = new ApexCharts(document.querySelector("#chart2"), options);
            chart.render();

            /*----chart2 end ----*/
            /*----chart3 start ----*/

            var progressList = '<?=json_encode($this->chart3)?>';
        
            var progressList = JSON.parse(progressList);
            console.log(progressList,'progressList')
            var progressListNew = new Array();
            var totalVal = 0
            $.each(progressList,function(key,value){
                totalVal += parseInt(value);
                progressListNew.push(parseInt(value));
            })
        
            var options = {
                series: progressListNew,
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
                                    return totalVal
                                }
                            }
                        }
                    }
                },
                labels: ['Total Select', 'Not Available', 'Pending', 'Responded/Pipeline', 'No show', 'Confirmed'],
            };

            var chart = new ApexCharts(document.querySelector("#chart3"), options);
            chart.render();

            /*----chart3 end ----*/
            /*----chart4 start ----*/

            var chart4 = '<?=json_encode($this->chart4)?>';
            var chart4 = JSON.parse(chart4);

            var chart4New = new Array();
            $.each(chart4,function(key,value){
                chart4New.push(parseInt(value));
            })

            var options = {
                series: chart4New,
                chart: {
                    width: 400,
                    type: 'donut',
                },
                labels: ['BGV Sent', 'BGV Pending', 'BGV Cleared', 'On-Board', 'Released'],
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
                }],
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
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart4"), options);
            chart.render();

            /*----chart4 end ----*/
      
    });
    </script>
<?php TemplateUtility::printFooter(); ?>
