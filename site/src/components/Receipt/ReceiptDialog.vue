<template>
<div v-if="visible">
    <v-dialog
		v-model="visible"
		max-width="75vw"
    >
		<EditReceipt v-if="options.mode === defaults.mode[1]" ref="editReceipt" :mode="options.mode" @save="save" @close="hide"></EditReceipt>
		<ViewReceipt v-if="options.mode === defaults.mode[0]" :receipt="receipt" @close="hide"></ViewReceipt>
    </v-dialog>

</div>
</template>

<script>
import EditReceipt from './EditReceipt';
import ViewReceipt from './ViewReceipt';
export default {
	beforeMount() {
		this.init();
	},
	props: {
		dialogMode: {
			type: String,
			required: false
		},
	},
    data: function () {
        return {
			visible:false,
			defaults: {
				mode: ['View','Edit'],
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
	},
	provide: function () {
		return {
			getReceipt: this.getReceipt
		}
	},
	methods: {
		init() {
			this.options.mode = this.defaults.mode[0];
			console.log(`mode: ${this.options.mode}`);
			if(typeof this.dialogMode === 'undefined') {
				if(this.defaults.mode.indexOf(this.dialogMode) !== -1 ) {
					this.options.mode = this.dialogMode;
				}
			}
		},
		show() {
			// this.$refs.editReceipt.receipt = this.receipt;
			this.visible = true;
		},

		hide() {
			this.visible = false;
			this.init();
		},
		getReceipt() {
			return this.receipt;
		},
		save() {
			//ping to place
			let formData = new FormData();
			formData.append('receipt', JSON.stringify(this.receipt));
			console.log(`${this.$store.state.api}/receipt/${this.receipt.id}`);
			fetch(`${this.$store.state.api}/receipt/${this.receipt.id}`, {
				method: 'PATCH',
				body: (new URLSearchParams(formData)),
				credentials: 'include'
			}).then(response => {
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				console.log(data);
			}).catch(err => {
				console.error(err);
			})
			this.hide();
		}
	}
}
</script>

<style scoped>
.receipt {
	font-size: 3em !important;
}
</style>