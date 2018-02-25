var BusinessOverview = (function(){

    function init(){
        var k, tmp_obj, prev_12_months_vs_now_data, near_upcoming_invoices_data, near_upcoming_invoices_dataset, near_upcoming_invoices_dataset_labels, near_upcoming_invoices_chart,
            prev_12_months_vs_now_data_elem = document.querySelector('input[name="prev_12_months_vs_now_data"]'),
            near_upcoming_invoices_data_elem = document.querySelector('input[name="near_upcoming_invoices_data"]');
        
        if( prev_12_months_vs_now_data_elem ){
            prev_12_months_vs_now_data = JSON.parse( prev_12_months_vs_now_data_elem.value );
            if( prev_12_months_vs_now_data ){
                Morris.Area({
                    element: 'prev_12_months_vs_now_chart',
                    data: prev_12_months_vs_now_data ,
                    xkey: 'period',
                    ykeys: ['iphone', 'ipad', 'itouch'],
                    labels: ['iphone', 'ipad', 'itouch'],
                    pointSize: 0,
                    lineWidth:0,
                    fillOpacity: 0.6,
                    pointStrokeColors:['#2ecd99', '#4e9de6', '#f0c541'],
                    behaveLikeLine: true,
                    grid: false,
                    hideHover: 'auto',
                    lineColors: ['#2ecd99', '#4e9de6', '#f0c541'],
                    resize: true,
                    redraw: true,
                    smooth: true,
                    gridTextColor:'#878787',
                    gridTextFamily:"Poppins",
                    parseTime: false,
                });
            }
            prev_12_months_vs_now_data_elem.parentNode.removeChild(prev_12_months_vs_now_data_elem);
        }

        if( near_upcoming_invoices_data_elem ){
            near_upcoming_invoices_data = JSON.parse( near_upcoming_invoices_data_elem.value );
            near_upcoming_invoices_dataset = [];
            near_upcoming_invoices_dataset_labels = null;
            for(k in near_upcoming_invoices_data){
                if( near_upcoming_invoices_data.hasOwnProperty(k) ){
                    
                    // console.log( k, near_upcoming_invoices_data[k] );

                    if( null === near_upcoming_invoices_dataset_labels ){
                        near_upcoming_invoices_dataset_labels = Object.keys( near_upcoming_invoices_data[k].data );
                    }

                    tmp_obj = {
                        label: near_upcoming_invoices_data[k].label,
                        data: Object.values( near_upcoming_invoices_data[k].data ),
                    };

                    switch( parseInt(k, 10) ){
                        case 0:
                            tmp_obj.backgroundColor = 'rgba(240,197,65,.6)';
                            tmp_obj.borderColor = 'rgba(240,197,65,.6)';
                            break;
                        case 1:
                            tmp_obj.backgroundColor = 'rgba(46,205,153,.6)';
                            tmp_obj.borderColor = 'rgba(46,205,153,.6)';
                            break;
                        case 2:
                            tmp_obj.backgroundColor = 'rgba(78,157,230,.6)';
                            tmp_obj.borderColor = 'rgba(78,157,230,.6)';
                            break;
                    }

                    near_upcoming_invoices_dataset.push( tmp_obj );
                }
            }

            near_upcoming_invoices_chart = document.getElementById("near_upcoming_invoices_chart");

            if( near_upcoming_invoices_chart ){
                new Chart( near_upcoming_invoices_chart.getContext("2d"), {
                    type:"bar",
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July"],
                        datasets: near_upcoming_invoices_dataset,
                    },
                    options: {
                        tooltips: {
                            mode:"label"
                        },
                        scales: {
                            yAxes: [
                                {
                                    stacked: true,
                                    gridLines: {
                                        color: "rgba(135,135,135,0)",
                                    },
                                    ticks: {
                                        fontFamily: "Poppins",
                                        fontColor:"#878787"
                                    }
                                }
                            ],
                            xAxes: [
                                {
                                    stacked: true,
                                    gridLines: {
                                        color: "rgba(135,135,135,0)",
                                    },
                                    ticks: {
                                        fontFamily: "Poppins",
                                        fontColor:"#878787"
                                    }
                                }
                            ],
                        },
                        elements:{
                            point: {
                                hitRadius:40
                            }
                        },
                        animation: {
                            duration: 3000
                        },
                        responsive: true,
                        maintainAspectRatio:false,
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            backgroundColor:'rgba(33,33,33,1)',
                            cornerRadius:0,
                            footerFontFamily:"'Poppins'"
                        }
                    }
                });
            }
        }
    }

    return {
        init: init,
    }
}());

(function(){
    "use strict";
    BusinessOverview.init();
}());