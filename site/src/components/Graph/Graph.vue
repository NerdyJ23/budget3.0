<template>
	<v-card>
		<v-card-title>
			{{monthStruct[month]}}
		</v-card-title>
		<v-card-text>
			<div>
			Chart Type:
				<v-switch
					v-model=options.type
					false-value="column"
					true-value="pie"
					:label="options.type === 'column' ? 'Column' : 'Pie'"
				>

				</v-switch>
			</div>
			<div :style=chartHeight>
				<div id="budgetChart" class="chart" style="width:50%"></div>
			</div>
		</v-card-text>
	</v-card>
</template>

<script>
export default {
	mounted() {
		console.log(this.dataPoints);
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
			monthStruct: this.$store.state.months,
			month: 0,
			year: 0,
			height: 0,
			sortedData: null,
			options: {
				animationEnabled: true,
				animationDuration: 500,
				type: 'column',
				axisX: {
					title: "Month",
					interval: 0
				},
				axisY: {
					suffix: "$"
				},
				legend: {
					horizontalAlign: "right",
					verticalAlign: "center"
				},
				data: []
			},
			chartRef: null
		}
	}
	,methods: {
		generateGraph() {
			this.options.data = [];
			if(this.sortedData === null) {
				let tempData = [];
				for(let receipt in this.dataPoints) {
					console.log(this.dataPoints[receipt]);
					for(let i in this.dataPoints[receipt].items) {
						const item = this.dataPoints[receipt].items[i];
						console.log(item);
						if(typeof tempData[item.category] === 'undefined') {
							tempData[item.category] = {
								name: item.category,
								cost: 0
							};
						}
						tempData[item.category].cost += item.cost * item.count;
					}
				}
				this.sortedData = [];
				for (let item in tempData) {
					this.sortedData.push(tempData[item]);
				}
				this.sortedData.sort(function (a,b) {return a.cost - b.cost});
			}
			let pos = 0;

			if(this.options.type === 'pie') {
				this.options.data.push({
					type: "pie",
					showInLegend: true,
					yValueFormatString: "$#####",
					dataPoints: []
				});
				for(let i in this.sortedData) {
					if(this.sortedData[i].cost <= 0) {
						console.log(this.sortedData[i]);
						continue;
					}
					let item = {
						legendText: this.sortedData[i].name,
						name: this.sortedData[i].name,
						label: this.sortedData[i].name,
						x: pos,
						y: this.sortedData[i].cost,
					}
					this.options.data[0].dataPoints.push(item);
					pos++;
				}
			} else if (this.options.type === 'column') {
				this.options.data = [];
				for(let i in this.sortedData) {
					if(this.sortedData[i].cost <= 0) {
						console.log(this.sortedData[i]);
						continue;
					}
					let item = {
						type: 'column',
						legendText: this.sortedData[i].name,
						name: this.sortedData[i].name,
						yValueFormatString: "$#####",
						showInLegend: true,
						dataPoints:[{
							label: this.sortedData[i].name,
							x: pos,
							y: this.sortedData[i].cost
						}]
					}
					this.options.data.push(item);
					pos++;
				}
			}
			var chart = new CanvasJS.Chart('budgetChart', this.options);
			chart.render();

			this.chartRef = chart;
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
			return `height: ${temp + 25}px`;
		}
	}
	,watch: {
		'options.type'() {
			this.generateGraph();
		}
	}
}
</script>