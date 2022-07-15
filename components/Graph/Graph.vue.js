Vue.component('budget-graph', {
	template: `
	<v-card>
		<v-card-title>
			{{monthStruct[month]}}
		</v-card-title>
		<v-card-text>
			<div id="chart" class="chart" @click="toggleTooltip"></div>
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
					suffix: "%"
				},
				toolTip: {
					shared: true,
					reversed: true
				},
				legend: {
					horizonalAlign: "left",
					reversed: true
				},
				data: []
			}
		}
	}
	,methods: {
		generateGraph() {
			console.log(this.dataPoints);
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
			// console.log(tempData);
			for(let i in tempData) {
				if(tempData[i].cost <= 0) {
					console.log(tempData[i]);
					continue;
				}
				let item = {
					type: "stackedColumn100",
					showInLegend: true,
					name: tempData[i].name,
					dataPoints: [{
						label: this.monthStruct[this.month],
						y: tempData[i].cost,
						yValueFormatString: "$#####"
					}]
				}
				this.options.data.push(item);
			}
			var chart = new CanvasJS.Chart("chart",this.options);
			chart.render();
		}
		,toggleTooltip() {
			$("#chart").CanvasJSChart().toolTip.shared = $("#chart").CanvasJSChart().toolTip.shared;
		}
	}
})