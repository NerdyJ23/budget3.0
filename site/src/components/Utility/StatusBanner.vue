<template>
	<v-row class="d-flex text-center">
		<span
		class="text-h5 white--text red flex-grow-1"
		v-if="status === defaults.status[2]"
		>{{statusText}}</span>

		<span
		class="text-h5 white--text green flex-grow-1"
		v-else-if="status === defaults.status[1]"
		>{{statusText}}</span>
	</v-row>
</template>
<script>
export default {
	props: {
		statusP: {
			type: String,
			required: false
		},
		statusTextP: {
			type: String,
			required: false
		}
	},
	mounted() {
		this.init();
	},
	data: function () {
		return {
			status: '',
			statusText: '',
			defaults: {
				status: ['None', 'Success', 'Fail']
			}
		}
	},
	methods: {
		init() {
			if(this.isValidStatus(this.statusP)) {
				this.status = this.statusP;
			} else {
				this.status = this.defaults.status[0];
			}
			if(typeof this.statusTextP !== 'undefined') {
				this.statusText = this.statusTextP;
			} else {
				this.statusText = '';
			}
		},
		setStatus(status) {
			if(!this.isValidStatus(status)) {
				return;
			}
			switch(status.toLowerCase()) {
				case this.defaults.status[0].toLowerCase():
					this.status = this.defaults.status[0];
					break;
				case this.defaults.status[1].toLowerCase():
					this.status = this.defaults.status[1];
					break;
				case this.defaults.status[2].toLowerCase():
					this.status = this.defaults.status[2];
					break;
			}
		},
		isValidStatus(status) {
			if(typeof status !== 'undefined') {
				if(status.toLowerCase() === this.defaults.status[0].toLowerCase()
				|| status.toLowerCase() === this.defaults.status[1].toLowerCase()
				|| status.toLowerCase() === this.defaults.status[2].toLowerCase()) {
					return true;
				}
			}
			return false;
		},
		setStatusMessage(text) {
			this.statusText = text;
		}
	}
}
</script>