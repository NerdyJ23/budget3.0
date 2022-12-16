<template>
	<v-dialog
	v-model="options.visible"
	max-width="75vw"
	>
		<v-card>
			<v-card-title>{{options.mode}}</v-card-title>

			<v-form ref="login" class="mx-4" @submit.prevent="login" :disabled="options.disabled">
				<v-card-text>
					<v-overlay
						v-if="options.loading"
						:absolute="options.absoluteOverlay"
					>
						<v-progress-circular indeterminate size="50"></v-progress-circular>
					</v-overlay>
					<StatusBanner ref="status"></StatusBanner>
					<v-row>
						<v-text-field
						label="username"
						v-model="username"
						prepend-icon="mdi-account"
						ref="username"
						autofocus
						></v-text-field>
					</v-row>
					<v-row>
						<v-text-field
						label="password"
						v-model="password"
						prepend-icon="mdi-form-textbox-password"
						type="password"
						></v-text-field>
					</v-row>
				</v-card-text>

				<v-card-actions class="d-flex justify-end">
					<v-btn color="green lighten-2" @click="login" type="submit">Login</v-btn>
					<v-btn outlined color="red" @click="options.visible = false">Cancel</v-btn>
				</v-card-actions>
			</v-form>
		</v-card>


	</v-dialog>
</template>

<script>
import StatusBanner from '../Utility/StatusBanner.vue';
import cakeApi from "../../services/cakeApi";

export default {
	mounted() {
		this.init();
	},
	data: function() {
		return {
			username: '',
			password: '',
			rememberUsername: false,
			defaults: {
				modes: ['Login', 'Register'],
			},
			options: {
				visible: false,
				loading: false,
				mode: '',
				disabled: false,
				absoluteOverlay: true,
			}
		}
	},
	components: {
    StatusBanner,
},
	methods: {
		init() {
			this.options.mode = this.defaults.modes[0];
			this.username = '';
			this.password = ''
		},
		show() {
			this.init();
			this.options.visible = true;
			setTimeout(() => {this.$refs.status.init()}, 1000);
		},
		hide() {
			this.options.visible = false;
		},
		async login() {
			this.isLoading(true);
			const response = await cakeApi.login(this.username, this.password);

			if (response.status <= 300) {
				console.error(`${response.status}: ${response.statusText}`);
				this.isLoading(false);
				this.$refs.status.setStatus('Fail');
				this.$refs.status.setStatusMessage(response.statusText);
				this.password = '';
			} else {
				this.$refs.status.setStatus('Success');
				this.$refs.status.setStatusMessage('Success! Redirecting...');
				setTimeout(() => {
					this.options.visible = false
					this.isLoading(false);
					},1000);
				this.$emit('loggedin');
			}

			// try {
			// 	fetch(`${this.$store.state.api}/login`, {
			// 		method: 'POST',
			// 		credentials: 'include',
			// 		body: (new URLSearchParams(formData))
			// 	}).then(response => {
			// 		if (response.status === 200) {

			// 		} else {
			// 			this.isLoading(false);
			// 			throw response;
			// 		}
			// 	}).catch((error) => {

			// 	})
			// } catch(e) {
			// 	this.isLoading(false);
			// 	this.$refs.status.setStatus('Fail');
			// 	this.$refs.status.setStatusMessage('Unknown Error Occured');
			// 	this.password = '';
			// }
		},
		isLoading(value) {
			this.$refs.status.init();
			this.options.disabled = value;
			this.loading = value;
		}
	}
}
</script>