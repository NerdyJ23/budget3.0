Vue.component('budget-graph', {
	template: `
	<v-card>
		<v-card-title>
			{{monthStruct[month]}}
		</v-card-title>
		<v-card-text>
			<div id="barChart" class="chart" style="width:50%"></div>
			<div id="pieChart" style="width:50%"></div>
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
			options: {
				animationEnabled: true,
				title: {
					text: "Budget"
				},
				labels: [],
				type: 'bar',
				axisX: {
					title: "Month"
				},
				axisY: {
					suffix: "$"
				},
				toolTip: {
					shared: true,
					reversed: true
				},
				legend: {
					horizonalAlign: "left",
					reversed: true
				},
				data: [{
					type: "column",
					showInLegend: true,
					yValueFormatString: "$#####",
					dataPoints: []
				}]
			}
		}
	}
	,methods: {
		generateGraph() {
			// console.log(this.dataPoints);
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
					label: sortedData[i].name,
					x: pos,
					y: sortedData[i].cost,
				}
				this.options.data[0].dataPoints.push(item);
				// this.options.axisX.maximum = i;
				pos++;
			}
			this.options.data[0].name = this.currentMonth;
			var chart = new CanvasJS.Chart("barChart",this.options);
			chart.render();
		}
		,toggleTooltip() {
			$("#chart").CanvasJSChart().toolTip.shared = $("#chart").CanvasJSChart().toolTip.shared;
		}
	}
	,computed: {
		currentMonth() {
			return this.monthStruct[this.month];
		}
	}
})