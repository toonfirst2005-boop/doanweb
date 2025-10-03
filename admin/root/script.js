$(document).ready(function() {
	$.ajax({
		url: 'get_cash_earned.php',
		type: 'get',
		dataType: 'json',
		data: {days: 30},
	})
	.done(function(response) {
		var arrayX = Object.keys(response)
		var arrayY = Object.values(response)
		Highcharts.chart('container', {

			title: {
				text: 'Thống kê doanh thu 30 ngày gần nhất'
			},

			yAxis: {
				title: {
					text: 'Doanh thu'
				}
			},

			xAxis: {
				categories: arrayX
			},

			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					},
				}
			},

			series: [{
				name: 'Doanh thu',
				data: arrayY
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
	})
	
})
$(document).ready(function() {
	$.ajax({
		url: 'get_receipts.php',
		type: 'get',
		dataType: 'json',
		data: {days},
	})
	.done(function(response) {
		const array = Object.values(response)
		get_chart(array, days)
	})
	
	function get_chart(array, days) {
		Highcharts.chart('container_2', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Thống kê hóa đơn theo trạng thái trong ' + days + ' gần đây'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					}
				}
			},
			series: [{
				name: 'Brands',
				colorByPoint: true,
				data: array
			}]
		});
	}
	
})

$(document).ready(function() {
	$.ajax({
		url: 'get_cash_vip_customer.php',
		dataType: 'json',
	})
	.done(function(response) {
		var arrayX1 = Object.keys(response)
		var arrayY1 = Object.values(response)
		console.log(arrayX1)
		console.log(arrayY1)
		get_chart_customer(arrayX1, arrayY1)
	})
})

function get_chart_customer(arrayX1, arrayY1) {
	Highcharts.chart('container_3', {
		chart: {
			type: 'cylinder',
			options3d: {
				enabled: true,
				alpha: 15,
				beta: 15,
				depth: 50,
				viewDistance: 25
			}
		},
		title: {
			text: 'Khách hàng thân thiết'
		},
		plotOptions: {
			series: {
				depth: 25,
				colorByPoint: true
			}
		},
		xAxis: {
			title: {
				text: 'Số tiền khách hàng đầu tư'
			},
			categories: arrayX1,
			labels: {
				skew3d: true,
				style: {
					fontSize: '16px'
				}
			}
		},
		yAxis: {
			title: {
				text: 'Đồng'
			}
		},
	series: [{
		data: arrayY1,
		name: 'Số tiền đầu tư',
		showInLegend: false
	}]
})
}

// Order Status Pie Chart
$(document).ready(function() {
	$.ajax({
		url: 'get_order_status.php',
		type: 'get',
		dataType: 'json',
	})
	.done(function(response) {
		Highcharts.chart('container_2', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Trạng thái đơn hàng',
				style: {
					fontSize: '16px',
					fontWeight: 'bold'
				}
			},
			tooltip: {
				pointFormat: '<b>{point.y}</b> đơn hàng<br/><b>{point.percentage:.1f}%</b> tổng số đơn'
			},
			accessibility: {
				point: {
					valueSuffix: '%'
				}
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b><br/>{point.percentage:.1f}%',
						style: {
							fontSize: '12px'
						}
					},
					showInLegend: true,
					size: '80%'
				}
			},
			legend: {
				align: 'bottom',
				verticalAlign: 'bottom',
				layout: 'horizontal',
				itemStyle: {
					fontSize: '11px'
				}
			},
			series: [{
				name: 'Đơn hàng',
				colorByPoint: true,
				data: response
			}]
		});
	})
	.fail(function() {
		console.error('Failed to load order status data');
	});
});

// Top Products Chart
$(document).ready(function() {
	$.ajax({
		url: 'get_top_products.php',
		type: 'get',
		dataType: 'json',
	})
	.done(function(response) {
		Highcharts.chart('container_1', {
			chart: {
				type: 'column'
			},
			title: {
				text: 'Sản phẩm bán chạy (30 ngày)',
				style: {
					fontSize: '16px',
					fontWeight: 'bold'
				}
			},
			subtitle: {
				text: 'Top 10 sản phẩm có số lượng bán cao nhất'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '11px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: 'Số lượng bán'
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '<b>{point.y}</b> sản phẩm đã bán<br/>Doanh thu: <b>{point.revenue:,.0f}₫</b>'
			},
			plotOptions: {
				column: {
					dataLabels: {
						enabled: true,
						format: '{point.y}'
					},
					colorByPoint: true
				}
			},
			series: [{
				name: 'Số lượng bán',
				data: response.map(function(item) {
					return {
						name: item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name,
						y: item.y,
						color: item.color,
						revenue: item.revenue
					};
				})
			}]
		});
	})
	.fail(function() {
		console.error('Failed to load top products data');
	});
});