<template>
	<v-card>
		<v-card-title class="accent">
			Monthly Overview
		</v-card-title>
		<v-card-text>
			<v-row class="pt-4">
				<v-col cols="8">
					<canvas ref="budgetChart" v-show="dataPoints.length > 0"></canvas>
					<v-card-title v-show="dataPoints.length == 0">No Data Found</v-card-title>
				</v-col>
				<v-col cols="4">
					<v-row>
						<v-col cols="7">
							<v-select
								:items="GenericStore.months"
								v-model="month"
								@change="load()"
								label="Month"
							/>
						</v-col>
						<v-col cols="5">
							<v-select
								:items="year.list"
								v-model="year.selected"
								@change="load()"
								label="Year"
							/>
						</v-col>
					</v-row>
					<v-row>
						<v-col cols="3" class="d-flex align-center justify-end">
							Chart Type:
						</v-col>
						<v-col cols="9">
							<v-btn-toggle v-model="chartType.selected" mandatory>
								<v-btn>
									<v-icon>mdi-chart-bar</v-icon>
								</v-btn>
								<v-btn>
									<v-icon>mdi-chart-pie</v-icon>
								</v-btn>
							</v-btn-toggle>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>

<script>
import Chart from 'chart.js/auto';
import { mapState } from "vuex";
import cakeApi from "../../services/cakeApi";

export default {
	mounted() {
		this.init();
		this.load();
	}
	,data: function () {
		return {
			month: 0,
			year: {
				selected: 0,
				list: []
			},
			chartType: {
				selected: 0,
				list: ['bar', 'pie']
			},
			height: 200,
			// sortedData: null,
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
			dataPoints: [],
			chartRef: null
		}
	}
	,methods: {
		init() {
			const today = new Date();
			this.month = this.GenericStore.months[today.getMonth()];
			this.year.selected = today.getFullYear();
		},
		async load() {
			const today = new Date();
			const selectedMonth = this.GenericStore.months.indexOf(this.month) > -1 ? this.GenericStore.months.indexOf(this.month) : today.getMonth();
			const response = await cakeApi.listReceipts(selectedMonth+1, this.year.selected);
			if (response.status >= 300) {
				console.error(response.statusText);
			} else {
				const data = response.data;
				for(let y in data.years) {
					this.year.list.push(data.years[y].date);
				}

				let thisYear = this.year.list.find((year) => {
					return year === today.getFullYear()
				});

				if(this.year.list.length === 0 || typeof thisYear === 'undefined') {
					this.year.list.push(today.getFullYear());
				}
				this.dataPoints = data.result;
				this.generateGraph(this.config.type);
			}
		},
		generateGraph(type) {
			if (this.chartRef != null) {
				this.chartRef.destroy();
			}
			this.$nextTick(() => {
				this.config.type = type;
				this.generateBarGraph();
				// if(type === "bar") {
				// } else if (type === "pie") {
				// 	this.generatePieGraph();
				// }
			});
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

			if (this.config.type == "pie") {
				this.config.data.datasets.push({data: []});
				this.config.data.datasets[0].backgroundColor = [];
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

				if (this.config.type == "bar") {
					temp.data[labels[item]] = values[item];
					this.config.data.datasets.push(temp);
				} else if (this.config.type == "pie") {
					this.config.data.datasets[0].data.push(values[item]);
					this.config.data.labels.push(labels[item]);
					this.config.data.datasets[0].backgroundColor.push(temp.backgroundColor);
				}
			}

			this.config.options.plugins.title.text = this.currentMonth;
			this.config.options.onHover = this.hoverBar;
			// this.config.options.mouseOut = this.leaveBar;

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
			if (this.config.type != "bar") {
				return;
			}
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
			return this.month;
		}
	}
	,watch: {
		"chartType.selected"(){
			if (this.chartRef != null) {
				this.chartRef.destroy();
			}
			this.config.type = this.chartType.list[this.chartType.selected];
			this.generateGraph(this.config.type);
		}
	}
}
</script>