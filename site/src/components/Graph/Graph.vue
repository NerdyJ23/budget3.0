<template>
	<v-card>
		<v-card-title class="accent">
			Monthly Overview
		</v-card-title>
		<v-card-text>
			<v-row class="pt-4">
				<v-col cols="8">
					<canvas ref="budgetChart" v-show="hasData" style="max-height: 50vh"></canvas>
					<v-card-title v-show="!hasData">No Data Found</v-card-title>
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
								<v-btn :disabled="!hasData">
									<v-icon>mdi-chart-bar</v-icon>
								</v-btn>
								<v-btn :disabled="!hasData">
									<v-icon>mdi-chart-pie</v-icon>
								</v-btn>
							</v-btn-toggle>
						</v-col>
					</v-row>
				</v-col>
			</v-row>
			<v-row v-if="hasData">
				<v-col cols="auto">
					<GraphLegend :items="legendItems"/>
				</v-col>
			</v-row>
		</v-card-text>
	</v-card>
</template>

<script>
import Chart from 'chart.js/auto';
import GraphLegend from "./GraphLegend";
import { mapState } from "vuex";
import cakeApi from "../../services/cakeApi";

export default {
	name: "Graph",
	components: {
		GraphLegend
	},
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
				list: ['bar', 'doughnut']
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
					hover: {
						mode: "point"
					},
					plugins: {
						title: {
							display: false,
						},
						legend: {
							display: false,
						}
					},
					animation: {
						animateRotate: true,
						colors: true,
					},
					cutout: "70%",
					hoverOffset: 25
				},
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

			if (this.isPie) { //pie
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

					red: Math.floor(Math.random() * 255),
					green: Math.floor(Math.random() * 255),
					blue: Math.floor(Math.random() * 255),
				};

				temp.backgroundColor = this.generateBarColor(temp);
				if (this.isBar) {
					temp.data[labels[item]] = values[item];
					this.config.data.datasets.push(temp);
				} else if (this.isPie) {
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

		generateBarColor(barObj, hovered) {
			const alpha = hovered ? 0.2 : 1;
			return `rgba(${barObj.red}, ${barObj.green}, ${barObj.blue}, ${alpha})`;
		},

		handleHover(evt, item, legend) {
			console.log('handle hover');
			console.log(item);
			console.log(legend);
			for(const dataset of legend.chart.data.datasets) {
				if(dataset.label !== item.text) {
					dataset.backgroundColor = this.generateBarColor(dataset, true);
				}
			}
		},
		handleLeave(evt, item, legend) {
			legend.chart.update();
			for(const dataset of legend.chart.data.datasets) {
				dataset.backgroundColor = this.generateBarColor(dataset);
			}
			legend.chart.update();

		},

		hoverBar(evt, item, chart) {
			if (!this.isBar) {
				return;
			}
			console.log('bar hover');
			console.log(item);
			let index = -1;
			if(item.length > 0) {
				index = item[0].datasetIndex;
				for(const bar in chart.config._config.data.datasets) {
					const changeBg = index == -1 || item[0].datasetIndex != bar;
					chart.config._config.data.datasets[bar].backgroundColor = this.generateBarColor(chart.config._config.data.datasets[bar], changeBg);
				}
				chart.update();
			} else {
				for(const bar of chart.config._config.data.datasets) {
					bar.backgroundColor = this.generateBarColor(bar, false);
				}
				chart.update();
			}
		},
	}
	,computed: {
		...mapState(["GenericStore"]),
		currentMonth() {
			return this.month;
		},
		legendItems() {
			if (this.hasData && this.config.data.datasets[0] != null) {
				let arr = [];
				if (this.isBar) {
					for (let item of this.config.data.datasets) {
						arr.push({
							"color": item.backgroundColor,
							label: item.label
						});
					}
					return arr;
				} else if (this.isPie) {
				}
			}
			return [];
		},
		isBar() {
			return this.config.type == this.chartType.list[0];
		},
		isPie() {
			return this.config.type == this.chartType.list[1];
		},
		hasData() {
			return this.dataPoints.length > 0;
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