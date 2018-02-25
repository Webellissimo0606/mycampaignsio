<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view(get_template_directory() . 'header_new');

?>

<!-- BEGIN PAGE CONTENT -->
	<div class="row">
	    <div class="col-sm-12 col-md-12">
            <?php if ($this->session->flashdata('type') == 'error'): ?>
              	<div class="alert alert-danger"><?php echo $this->session->flashdata('msg'); ?></div>
      		<?php endif; ?>
		        
		    <?php if ($this->session->flashdata('type') == 'success'): ?>
		        <div class="alert alert-success"><?php echo $this->session->flashdata('msg'); ?></div>   
		    <?php endif; ?>
	    </div>
	</div>	

	<div class="row">
		<div class="col-md-12">
			<div class="panel">  
				<div class="panel-header">
					<h3><strong>Keyword Research</strong> domainnaeme.com</h3>
				</div>
				
				<div class="panel-content refined-tab">
					<div class="nav-tabs3">
						<ul id="myTab6" class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab_overview"> <i class="icon-layers"></i> Overview</a></li>
							<li class=""><a data-toggle="tab" href="#tab_positions"> <i class="icon-trophy"></i> Positions</a></li>
							<li class=""><a data-toggle="tab" href="#tab_selection"> <i class="icon-puzzle"></i> Keyword Selection</a></li>
							<li class=""><a data-toggle="tab" href="#tab_domain_vs_domain"> <i class="icon-arrow-up-circle"></i> Domain vs Domain</a></li>
						</ul>
							<div class="tab-content ">
							
								<?php include('tab_overview.php'); ?>
								
								<?php include('tab_positions.php'); ?>

								<?php include('tab_selection.php'); ?>

								<?php include('tab_domain_vs_domain.php'); ?>
								
								
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>

  <?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$this->load->view(get_template_directory() . 'footer_new');
  ?>


<script type="text/javascript">
	// Create the chart

	$(function () {

        $.post('/serp/keywordresearch/keyword_position_distribution',function(data){
            $('#keyword_pos_dis').highcharts({
            chart: {
                type: 'column',
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                            '1-1',
                            '2-3',
                            '4-5',
                            '6-10',
                            '11-20',
                            '21-50',
                            '51-100'
                        ],
            },
            yAxis: {
                title: {
                    text: ''
                }

            },
            tooltip: {
                headerFormat: '',
                pointFormat: '<span style="color:{point.color}">Position in SERP: {point.name}<br/>Keywords in top </span>: <b>{point.y}</b>'
            },

            legend: false,

            series: [{
                colorByPoint: false,
                data: [{
                    name: '1-1',
                    y: data.data[0]
                }, {
                    name: '2-3',
                    y: data.data[1]
                }, {
                    name: '4-5',
                    y: data.data[2]
                }, {
                    name: '6-10',
                    y: data.data[3]
                }, {
                    name: '11-20',
                    y: data.data[4]
                }, {
                    name: '21-50',
                    y: data.data[5]
                }, {
                    name: '51-100',
                    y: data.data[6]
                }]
            }]
        });
        },'json')

        
$.post('/serp/keywordresearch/competitors_graph',function(data){
    if(data.status == 'success') {
        var res = [];
        for (var i=0;i<data.data.length;i++){
            res.push({
              x:data.data[i].keywords,
              y:data.data[i].visible,
              domain:data.data[i].domain,
              color: Highcharts.getOptions().colors[i]
            });
        }
        $('#competitors_graph').highcharts({

            chart: {
                type: 'bubble',
                plotBorderWidth: 1,
                zoomType: 'xy'
            },

            legend: {
                enabled: false
            },

            title: {
                text: ''
            },

            subtitle: {
                text: ''
            },

            xAxis: {
                gridLineWidth: 1,
                title: {
                    text: 'Precision'
                },
                labels: {
                    format: '{value}'
                },
                plotLines: [{
                    color: 'black',
                    dashStyle: 'dot',
                    width: 2,
                    value: 65,
                    label: {
                        rotation: 0,
                        y: 15,
                        style: {
                            fontStyle: 'italic'
                        },
                        text: 'Keyword visibility per domain'
                    },
                    zIndex: 3
                }]
            },

            yAxis: {
                startOnTick: false,
                endOnTick: false,
                title: {
                    text: 'Visibility'
                },
                labels: {
                    format: '{value}'
                },
                maxPadding: 0.2,
                plotLines: [{
                    color: 'black',
                    dashStyle: 'dot',
                    width: 2,
                    value: 50,
                    label: {
                        align: 'right',
                        style: {
                            fontStyle: 'italic'
                        },
                        text: 'Keywords visibility per domain',
                        x: -10
                    },
                    zIndex: 3
                }]
            },

            tooltip: {
                useHTML: true,
                headerFormat: '<table>',
                pointFormat: '<tr><th colspan="2"><h4>{point.domain}</h4></th></tr>' +
                    '<tr><th>Keywords:</th><td>{point.x}</td></tr>' +
                    '<tr><th>Visibility:</th><td>{point.y}</td></tr>',
                footerFormat: '</table>',
                followPointer: true
            },

            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.domain}'
                    }
                }
            },

            series: [{
                data:res
            }]

        });
    }

},'json')
    
    $.post('/serp/keywordresearch/getdomaintrend',function(data){
        var dates = [];
        var res = [];
        var keywords = [];

        var maxkeydate = '';
        var maxkey = -1;

        var minkeydate = '';
        var minkey = 999999;

        var maxvisdate = '';
        var maxvis = -1;

        var minvisdate = '';
        var minvis = 999999;

        for(var i=0;i<data.data.length;i++){
            dates.push(data.data[i].date);
            res.push(parseFloat(data.data[i].visible));
            keywords.push(parseInt(data.data[i].keywords));
            
            if(maxkey < parseInt(data.data[i].keywords)) {
               maxkey = parseInt(data.data[i].keywords);
               maxkeydate = data.data[i].date;     
            }
            if(minkey > parseInt(data.data[i].keywords)) {
               minkey = parseInt(data.data[i].keywords);
               minkeydate = data.data[i].date;     
            }

            if(maxvis < data.data[i].visible) {
               maxvis = data.data[i].visible;
               maxvisdate = data.data[i].date;     
            }
            if(minvis > data.data[i].visible) {
               minvis = data.data[i].visible;
               minvisdate = data.data[i].date;     
            }
            

        }
      
        
        $('#max_vis_trend_date').html(maxvisdate);
        $('#min_vis_trend_date').html(minvisdate);

        $('#max_vis_trend').html(maxvis);
        $('#min_vis_trend').html(minvis);

        $('#max_keyword_trend_date').html(maxkeydate);
        $('#min_keyword_trend_date').html(minkeydate);

        $('#max_keyword_trend').html(maxkey);
        $('#min_keyword_trend').html(minkey);


        $('#visibility_trend').highcharts({
                        chart: {
                            type: 'area'
                        },
                        title: {
                            text: ''
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: dates,
                            allowDecimals: false,
                            labels: {
                                formatter: function () {
                                    return this.value; // clean, unformatted number for year
                                }
                            }
                        },
                        yAxis: {
                            title: {
                                text: ''
                            },
                            labels: {
                                formatter: function () {
                                    return this.value;
                                }
                            }
                        },
                        tooltip: {
                           
                        },
                        plotOptions: {
                            area: {
                                marker: {
                                    enabled: false,
                                    symbol: 'circle',
                                    radius: 2,
                                    states: {
                                        hover: {
                                            enabled: true
                                        }
                                    }
                                }
                            }
                        },
                        series: [{
                            name: data.data[0].domain,
                            data: res
                        }]
                });


               $('#keywords_trend').highcharts({
                       chart: {
                           type: 'area'
                       },
                       title: {
                           text: ''
                       },
                       subtitle: {
                           text: ''
                       },
                       xAxis: {
                           categories: dates,
                           allowDecimals: false,
                           labels: {
                               formatter: function () {
                                   return this.value; // clean, unformatted number for year
                               }
                           }
                       },
                       yAxis: {
                           title: {
                               text: ''
                           },
                           labels: {
                               formatter: function () {
                                   return this.value;
                               }
                           }
                       },
                       tooltip: {
                           pointFormat: '{series.name} <b>{point.y:,.0f}</b>'
                       },
                       plotOptions: {
                           area: {
                               marker: {
                                   enabled: false,
                                   symbol: 'circle',
                                   radius: 2,
                                   states: {
                                       hover: {
                                           enabled: true
                                       }
                                   }
                               }
                           }
                       },
                       series: [{
                           name: 'Keywords',
                           data: keywords
                       }]
               }); 







    },'json')

    

            
         });


	
	 $('.knob').knob({
	        change : function (value) {
	            //console.log("change : " + value);
	        },
	        release : function (value) {
	            //console.log(this.$.attr('value'));
	            console.log("release : " + value);
	        },
	        cancel : function () {
	            console.log("cancel : ", this);
	        },
	        /*format : function (value) {
	            return value + '%';
	        },*/
	        draw : function () {

	            // "tron" case
	            if(this.$.data('skin') == 'tron') {

	                this.cursorExt = 0.3;

	                var a = this.arc(this.cv)  // Arc
	                    , pa                   // Previous arc
	                    , r = 1;

	                this.g.lineWidth = this.lineWidth;

	                if (this.o.displayPrevious) {
	                    pa = this.arc(this.v);
	                    this.g.beginPath();
	                    this.g.strokeStyle = this.pColor;
	                    this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, pa.s, pa.e, pa.d);
	                    this.g.stroke();
	                }

	                this.g.beginPath();
	                this.g.strokeStyle = r ? this.o.fgColor : this.fgColor ;
	                this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, a.s, a.e, a.d);
	                this.g.stroke();

	                this.g.lineWidth = 2;
	                this.g.beginPath();
	                this.g.strokeStyle = this.o.fgColor;
	                this.g.arc( this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
	                this.g.stroke();

	                return false;
	            }
	        }
	    });

	    // Example of infinite knob, iPod click wheel
	    var v, up=0,down=0,i=0,
	    	$idir = $("div.idir"),
	    	$ival = $("div.ival"),
	    	incr = function() { i++; $idir.show().html("+").fadeOut(); $ival.html(i); },
	    	decr = function() { i--; $idir.show().html("-").fadeOut(); $ival.html(i); };
	    $("input.infinite").knob(
	                        {
	                        min : 0, 
	                        max : 20, 
	                        stopper : false, 
	                        change : function () {
	                                        if(v > this.cv){
	                                            if(up){
	                                                decr();
	                                                up=0;
	                                            }else{up=1;down=0;}
	                                        } else {
	                                            if(v < this.cv){
	                                                if(down){
	                                                    incr();
	                                                    down=0;
	                                                }else{down=1;up=0;}
	                                            }
	                                        }
	                                        v = this.cv;
	                                    }
	                        });
</script>