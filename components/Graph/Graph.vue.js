Vue.component('budget-graph', {
	template: `
	<v-card>
		<v-card-title>
			{{monthStruct[month]}}
		</v-card-title>
		<v-card-text>
			<canvas id="chart" class="chart"></canvas>
		</v-card-text>
	</v-card>
	`
	,inject: ["getChartJS"]
	,mounted() {
		this.getChartJS();
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
			config: {
				labels: [],
				type: 'bar',
				animationEnabled: true,
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
					verticalAlign: "center",
					horizonalAlign: "right",
					reversed: true
				},
				data: []
			}
		}
	}
	,methods: {
		generateGraph() {
			// console.log(this.dataPoints);
			for(let i in this.dataPoints) {
				let item = {
					type: "stackedColumn100",
					name: this.dataPoints[i].name,
				}
				this.options.data.push(item);
			}
			console.log(this.options);
			const chartLoc = document.getElementById('chart').getContext('2d');
			const chart = new Chart(chartLoc,this.options);
		}
	}
})