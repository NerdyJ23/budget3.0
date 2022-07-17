Vue.component('budget-graph', {
	template: `
	<v-card>
		<v-card-title>
			{{monthStruct[month]}}
		</v-card-title>
		<v-card-text :style="chartHeight">
			<div id="barChart" class="chart" style="width:40%"></div>
			<div id="pieChart" style="width:40%; position:absolute; right:0"></div>
		</v-card-text>
	</v-card>
	`
	,mounted() {
		this.generateGraph();
	}
	,props: {
		dataPoints: {
			type: Array,
			required:false
		}
	}
	,data: function () {
		return {
			monthStruct: ['January','February','March','April','May','June','July','August','September','October','November','December'],
			month: 0,
			year: 0000,
			height: 0,
			options: {
				animationEnabled: true,
				labels: [],
				type: 'bar',
				axisX: {
					title: "Month"
				},
				axisY: {
					suffix: "$"
				},
				legend: {
					horizontalAlign: "right",
					verticalAlign: "center"
				},
				data: [{
					type: "column",
					showInLegend: true,
					yValueFormatString: "$#####",
					dataPoints: []
				}]
			},
			chartRef: null
		}
	}
	,methods: {
		generateGraph() {
			let tempData = [];
			this.dataPoints.forEach((item,index) => {
				if(typeof tempData[item.category] === 'undefined') {
					tempData[item.category] = {
						name: item.category,
						cost: 0
					}
				}
				tempData[item.category].cost += item.cost * item.count;
			})
			let sortedData = [];
			for (item in tempData) {
				sortedData.push(tempData[item]);
			}
			sortedData.sort(function (a,b) {return a.cost - b.cost});
			let pos = 0;
			for(let i in sortedData) {
				if(sortedData[i].cost <= 0) {
					console.log(sortedData[i]);
					continue;
				}
				let item = {
					legendText: sortedData[i].name,
					name: sortedData[i].name,
					label: sortedData[i].name,
					x: pos,
					y: sortedData[i].cost,
				}
				this.options.data[0].dataPoints.push(item);
				// this.options.axisX.maximum = i;
				pos++;
			}
			this.options.data[0].name = this.currentMonth;
			var barChart = new CanvasJS.Chart("barChart",this.options);
			barChart.render();

			let newoptions = $.extend(true, {}, this.options);
			newoptions.data[0].type = "pie";
			var pieChart = new CanvasJS.Chart('pieChart', newoptions);
			pieChart.render();

			this.chartRef = pieChart;
		}
	}
	,computed: {
		currentMonth() {
			return this.monthStruct[this.month];
		}
		,chartHeight() {
			let temp = 0;
			try {
				temp = this.chartRef.get("height");
			} catch(err) {
			}
			return `height: ${temp + 25}`;
		}
	}
})