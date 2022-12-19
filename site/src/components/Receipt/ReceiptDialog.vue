<template>
<div v-if="visible">
    <v-dialog
		v-model="visible"
		:max-width="width"
    >
		<EditReceipt v-if="isEdit" ref="editReceipt" :mode="options.mode" @save="save" @close="hide"></EditReceipt>
		<ViewReceipt v-else :receipt="receipt" @close="hide" key="view"></ViewReceipt>

		<StatusBanner ref="banner"></StatusBanner>
    </v-dialog>

</div>
</template>

<script>
import EditReceipt from './Edit/EditReceipt';
import ViewReceipt from './ReceiptView';
import StatusBanner from '../Utility/StatusBanner';
import cakeApi from '../../services/cakeApi';

export default {
	mounted() {
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
					receiptNumber: '',
					items: []
				}
			},
			receipt: {
				name: '',
				date: null,
				receiptNumber: '',
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
			getReceipt: this.getReceipt,
			isMobile: this.isMobile,
		}
	},
	methods: {
		init() {
			if (this.options.mode === '') {
				this.options.mode = this.defaults.mode[0];
			}
		},
		show() {
			this.visible = true;
			this.receipt = this.defaults.receipt;
		},

		hide() {
			this.visible = false;
			this.init();
		},
		getReceipt() {
			return JSON.stringify(this.receipt);
		},
		setReceipt(receipt) {
			this.receipt = JSON.parse(receipt);
		},
		setMode(mode) {
			if (this.validMode(mode)) {
				this.options.mode = mode;
			}
		},
		validMode(mode) {
			if (typeof mode !== 'undefined') {
				if (this.defaults.mode.indexOf(mode) !== -1) {
					return true;
				}
			}
			return false;
		},
		async save() {
			this.receipt = this.$refs.editReceipt.receipt;
			console.log(this.receipt);
			this.receipt.delete = this.$refs.editReceipt.delete;
			let response = null;
			if (this.options.mode === this.defaults.mode[2]) { //Add
				response = await cakeApi.createReceipt(this.receipt.name,
				this.receipt.location,
				this.receipt.date,
				this.receipt.receiptNumber,
				this.receipt.items);
			} else if (this.options.mode === this.defaults.mode[1]) { //Edit
				response = await cakeApi.updateReceipt(this.receipt);
			}

			if (response.status <= 300) {
				this.$refs.banner.setStatus('Success');
				this.$refs.banner.setStatusMessage('Saved successfully!');
				setTimeout(() => {
					this.hide();
					this.$emit('updated');
				}, 500);
			} else {
				this.$refs.banner.setStatus('Fail');
				this.$refs.banner.setStatusMessage('uhoh!');
			}
		},
		isMobile() {
			return this.$vuetify.breakpoint.xs;
		},
	},
	computed: {
		width() {
			//not view
			if(this.isEdit) {
				if(this.$vuetify.breakpoint.sm || this.isMobile) {
					return '100vw';
				}
				return '75vw';
			}
			if(this.isMobile || this.$vuetify.breakpoint.sm) {
				return '100vw';
			}
			if(this.$vuetify.breakpoint.md) {
				return '75vw';
			}

			return '50vw';
		},

		isEdit() {
			return this.defaults.mode[0] !== this.options.mode;
		}
	}
}
</script>
<style scoped>
:deep(.receipt) {
	font-size: 2em !important;
}
</style>