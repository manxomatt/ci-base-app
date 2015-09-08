"use strict";
$(function () {
	var auto_update = 1;
	var startDate;
	var endDate;
	
	var options_column = {
		chart:	{
           // renderTo: 'container_column',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 10,
                depth: 50,
                viewDistance: 25
            },
			events : {
				load : function () {
					setInterval(function () {
						if(auto_update == 1){
							//$.getJSON("http://localhost/ajax/hchart.php?&jsoncallback=?",function(json) {
							$.getJSON("http://182.23.32.90:85/JR/lokasi_samsat/json_data?jsoncallback=?",function(json) {
								chart_col.series[0].update(json[0]);
								//alert(moment()); );//&start_date="+startDate.format('YYYY/MM/DD')+"&end_date="+endDate.format('YYYY/MM/DD')
							});
						}
					}, 5000);
				} 
			}
        },
		legend: {
            enabled: false
        },
		yAxis: {
			title: {
				text: 'Jumlah (Rp)'
			},
			labels: {
				formatter: function () {
					return (this.value/1000000) + ' JT'; 
				}
			}
		},
		tooltip: {
			formatter:function(){//this.point.series.name
				return this.x+ '<br/> SWDKLLJ : <b>' + '<b> Rp. '+Highcharts.numberFormat(this.point.y,0,',','.')+'</b><br/>';
			}
		},
		title: {
		    text: 'Penerimaan SWDKLLJ Propinsi Lampung',
			x: -20 //center 
		}, 
		subtitle: {
			text: 'Jasa Raharja Lampung',
			x: -20
		},
        plotOptions: {
            column: {
                depth: 25,
				colorByPoint: true,
				dataLabels: {
					enabled: true,
					formatter:function(){
						return Highcharts.numberFormat(this.point.y,0,',','.')+'<br/>'+this.point.persen;
					}
				}
            }
        },
		colors: [
			'#dd4b39',
			'#f39c12',
			'#00c0ef'
		],
		series:[]
	}
	var chart_col = new Highcharts.Chart(options_column);
	$.getJSON("http://182.23.32.90:85/JR/lokasi_samsat/json_data?jsoncallback=?",function(json) {
		chart_col.addSeries({
			data : json[0]['data'],
			name : 'Wilayah'
		});
		chart_col.xAxis[0].setCategories(json[1]['data']);
		 
	});
	// */
	var chart_col = new Highcharts.Chart(options_column);
	
	var options = {
			chart: {
                    renderTo: 'container',
                    type: 'line',
                    events : {
						load : function () {

							// set up the updating of the chart each second
							//var series = this.series[0];
							setInterval(function () {
								if(auto_update == 1){
									//$.getJSON("http://localhost/ajax/hchart.php?&jsoncallback=?",function(json) {
									$.getJSON("http://182.23.32.90:85/JR/chart/json_data?&jsoncallback=?&start_date="+startDate.format('YYYY/MM/DD')+"&end_date="+endDate.format('YYYY/MM/DD'),function(json) {
										chart.series[0].update(json[0]);
										//alert(moment());
									});
								}
							}, 5000);
						} 
					}
                },
			title: {
			    text: 'Penerimaan SWDKLLJ Propinsi Lampung',// 30 Hari Terakhir',
				x: -20 //center 
			}, 
			subtitle: {
				text: 'Jasa Raharja Cabang Lampung',
				x: -20
			},
			xAxis: {
				title: "Tanggal",
				categories: [],
				labels: {
					formatter: function () {
						return this.value ; 
					}
				}
				
			},
			yAxis: {
				title: {
					text: 'Jumlah (Rp)'
				},
				plotLines: [{
					value: 0,
					width: 1,
					color: '#808080'
				}],
				labels: {
					formatter: function () {
						return (this.value/1000000) + ' JT'; 
					}
				}
			},
			tooltip: {
				formatter:function(){
					return this.x+ '<br/>' +this.point.series.name + ': <b>' + '<b> Rp. '+Highcharts.numberFormat(this.point.y,0,',','.')+'</b><br/>';
				}
			},
			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'top',
				borderWidth: 0,
				//x: -150,
				y: 20,
				floating:true
			},
			series:[],
			
		}
    /*
	var chart = new Highcharts.Chart(options);
	
	//$.getJSON("http://localhost/ajax/hchart.php?&jsoncallback=?",function(json) {
	$.getJSON("http://182.23.32.90:85/JR/chart/json_data?&jsoncallback=?",function(json) {
		chart.addSeries({
			data : json[0]['data']
		});
		chart.xAxis[0].setCategories(json[1]['data']);

	});
	*/
	 $('.daterange').daterangepicker(
          {
            ranges: {
              //'Hari ini': [moment(), moment()],
             // 'Kemarin': [moment().subtract('days', 1), moment().subtract('days', 1)],
              '7 Hari Terakhir': [moment().subtract('days', 6), moment()],
              '30 Hari Terakhir': [moment().subtract('days', 29), moment()],
              'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
              'Bulan Kemarin': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            startDate: moment().subtract('days', 29),
            endDate: moment()
          },
			function (start, end) {
				
				//$.getJSON("http://localhost/ajax/hchart.php?&jsoncallback=?",function(json) {
				$.getJSON("http://182.23.32.90:85/JR/chart/json_data?&jsoncallback=?&start_date="+start.format('YYYY/MM/DD')+"&end_date="+end.format('YYYY/MM/DD'),function(json) {
					chart.series[0].update(json[0]);
					chart.xAxis[0].setCategories(json[1]['data']);
					//alert(end.format('YYYYMMDD')+" - "+moment().format('YYYYMMDD'));
					startDate = start;
					endDate = end;  
					if(end.format('YYYYMMDD') == moment().format('YYYYMMDD')){
						auto_update = 1;
					}else{
						auto_update = 0; 
					}
				});
			//alert("Dari Tanggal : " + start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
	  });
});
