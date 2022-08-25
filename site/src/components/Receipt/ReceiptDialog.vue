<template>
<div v-if="visible">
    <v-dialog
		v-model="visible"
		max-width="75vw"
    >
		<EditReceipt v-if="options.mode === defaults.mode[1] || options.mode === defaults.mode[2]" ref="editReceipt" :mode="options.mode" @save="save" @close="hide"></EditReceipt>
		<ViewReceipt v-if="options.mode === defaults.mode[0]" :receipt="receipt" @close="hide"></ViewReceipt>

		<StatusBanner ref="banner"></StatusBanner>
    </v-dialog>

</div>
</template>

<script>
import EditReceipt from './EditReceipt';
import ViewReceipt from './ViewReceipt';
import StatusBanner from '../Utility/StatusBanner';

export default {
	beforeMount() {
		this.init();
	},
    data: function () {
        return {
			visible:false,
			defaults: {
				mode: ['View', 'Edit', 'Add'],
				receipt: {
					name: '',
					date: null,
					id: 0,
					items: []
				}
			},
			receipt: {
				name: '',
				date: null,
				id: 0,
				items: []
			},
			options: {
				mode: ''
			}
        }
    },
	components: {
		EditReceipt,
		ViewReceipt,
		StatusBanner,
	},
	provide: function () {
		return {
			getReceipt: this.getReceipt
		}
	},
	methods: {
		init() {
			if(this.options.mode === '') {
				this.options.mode = this.defaults.mode[0];
			}
			console.log(`mode: ${this.options.mode}`);
		},
		show() {
			// this.$refs.editReceipt.receipt = this.receipt;
			this.receipt = this.defaults.receipt;
			this.visible = true;
		},

		hide() {
			this.visible = false;
			this.init();
		},
		getReceipt() {
			return this.receipt;
		},
		setMode(mode) {
			if(this.validMode(mode)) {
				this.options.mode = mode;
			}
		},
		validMode(mode) {
			if(typeof mode !== 'undefined') {
				if(this.defaults.mode.indexOf(mode) !== -1) {
					return true;
				}
			}
			return false;
		},
		save() {
			let formData = new FormData();
			let url = '';
			let method = this.options.mode === this.defaults.mode[2] ? 'PUT' : 'PATCH';

			if(this.options.mode === this.defaults.mode[2]) { //Add
				formData.append('name', this.receipt.name);
				formData.append('date', this.receipt.date);
				formData.append('items', JSON.stringify(this.receipt.items));

				url = `${this.$store.state.api}/receipt/`;
			} else if(this.options.mode === this.defaults.mode[1]) { //Edit
				formData.append('receipt', JSON.stringify(this.receipt));
				url = `${this.$store.state.api}/receipt/${this.receipt.id}`;
			}
			fetch(url, {
				method: method,
				body: (new URLSearchParams(formData)),
				credentials: 'include'
			}).then(response => {
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				this.$refs.banner.setStatus('Success');
				this.$refs.banner.setStatusMessage('Saved successfully!');
				setTimeout(() => {this.hide()}, 1000);
				console.log(data);
			}).catch(err => {
				this.$refs.banner.setStatus('Fail');
				this.$refs.banner.setStatusMessage('uhoh!');
				console.error(err);
			})
		}
	}
}
</script>

<style scoped>
.receipt {
	font-size: 3em !important;
}
</style>