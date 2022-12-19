<template>
	<v-card elevation="0">
		<v-card-title>
			{{currentMonth}}
		</v-card-title>
		<v-card-text>
			<div>
				Chart Type:
				<v-switch
					v-model=config.type
					false-value="bar"
					true-value="pie"
					:label="config.type === 'bar' ? 'Bar' : 'Pie'"
				 />
			</div>
			<v-row class="d-flex flex-column align-center">
				<v-card class="pa-4" style="min-width:50vw;">
					<canvas ref="budgetChart"></canvas>
				</v-card>
			</v-row>
		</v-card-text>
	</v-card>
</template>

<script>
import Chart from 'chart.js/auto';
import { mapState } from "vuex";

export default {
	mounted() {
		this.generateGraph(this.config.type);
	}
	,props: {
		dataPoints: {
			type: Array,
			required:false
		}
	}
	,data: function () {
		return {
			month: 7,
			year: 0,
			height: 200,
			sortedData: null,
			config: {
				type: "bar",
				data: {
					datasets: [],
					labels: []
				},
				id: 0,
				options: {
					responsive: true,
					events: ["mousemove", "mouseout"],
					plugins: {
						title: {
							display: true,
							text:'undefined'

						},
						legend: {
							display: true,
						}
					}
				}
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
		},
		generateGraph(type) {
			if(type === "bar") {
				this.generateBarGraph();
			} else if (type === "pie") {
				this.generatePieGraph();
			}

		},
		generateBarGraph() {
			this.config.data.datasets = [];
			this.config.data.labels= [];

			const ctx = this.$refs.budgetChart;
			console.log(this.$refs.budgetChart);

			let values = [];
			let labels = [];

			for(const receipt of this.dataPoints) {
				for(const item of receipt.items) {
					if(labels.indexOf(item.category) === -1) {
						labels.push(item.category);
						values.push(item.cost * item.count);
					} else {
						var index = labels.indexOf(item.category);
						values[index] += (item.cost * item.count);
					}
				}
			}
			for(const item in labels) {
				let temp = {
					label: labels[item],
					data: {},
					skipNull: true,
					grouped: false,
					onHover: this.hoverBar,
					backgroundColor: `rgb(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`
				};
				temp.data[labels[item]] = values[item];
				this.config.data.datasets.push(temp);
			}

			this.config.options.plugins.title.text = this.currentMonth;
			this.config.options.onHover = this.hoverBar;
			// this.config.options.mouseOut = this.leaveBar;
			console.log(this.config);

			this.config.options.plugins.legend.onHover = this.handleHover;
			this.config.options.plugins.legend.onLeave = this.handleLeave;
			const chart = new Chart(ctx, this.config);

			this.chartRef = chart;
		},

		// Append '4d' to the colors (alpha channel), except for the hovered index
		handleHover(evt, item, legend) {
			for(const dataset of legend.chart.data.datasets) {
				if(dataset.label !== item.text) {
					dataset.backgroundColor = dataset.backgroundColor.slice(0, dataset.backgroundColor.lastIndexOf(','));
					dataset.backgroundColor += ', 0.2)'
				}
			}
			legend.chart.update();
		},

		// Removes the alpha channel from background colors
		handleLeave(evt, item, legend) {
			for(const dataset of legend.chart.data.datasets) {
				dataset.backgroundColor = dataset.backgroundColor.slice(0, dataset.backgroundColor.lastIndexOf(','));
				dataset.backgroundColor += ', 1)'
			}
			legend.chart.update();
		},

		hoverBar(evt, item, chart) {

			let alpha = "";
			let index = -1;
			if(item.length > 0) {
				alpha = ", 0.2)";
				index = item[0].datasetIndex;
			} else {
				alpha = ", 1)";
				this.leaveBar(evt, item, chart);
			}

			for(const bar in chart.config._config.data.datasets) {
				let bg = chart.config._config.data.datasets[bar].backgroundColor;
				bg = bg.slice(0, bg.lastIndexOf(","));

				let changeBg = false;
				if(index == -1) {
					changeBg = true;
					bg += alpha;
				} else if(item[0].datasetIndex != bar) {
					changeBg = true;
				}

				if(changeBg) {
					bg += alpha;
					chart.config._config.data.datasets[bar].backgroundColor = bg;
				}
			}
			chart.update();

		},
		leaveBar(evt, item, chart) {
						if(item.length > 0) {
				if(item[0].element.constructor.name === "BarElement") {
					console.log(chart.config._config);
					for(const bar in chart.config._config.data.datasets) {
					let bg = chart.config._config.data.datasets[bar].backgroundColor;
						if(item[0].datasetIndex !== bar) {
							bg = bg.slice(0, bg.lastIndexOf(","));
							bg += ", 1)";
							chart.config._config.data.datasets[bar].backgroundColor = bg;
							console.log(chart.update());
							console.log(chart.config._config.data.datasets[bar].backgroundColor);
						}

					}

				}
			}
		}
	}
	,computed: {
		...mapState(["GenericStore"]),

		currentMonth() {
			return this.GenericStore.months[this.month];
		}
	}
	,watch: {
		'config.type'() {
			this.chartRef.destroy();
			this.generateGraph(this.config.type);
		}
	}
}
</script>