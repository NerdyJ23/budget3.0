<template>
	<v-dialog
	v-model="options.visible"
	max-width="75vw"
	>
		<v-card>
			<v-card-title>{{options.mode}}</v-card-title>

			<v-form ref="login" class="mx-4" @submit.prevent="login" :disabled="options.disabled">
				<v-card-text>
					<StatusBanner ref="status"></StatusBanner>
					<v-progress-linear indeterminate v-if="options.loading"></v-progress-linear>
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
				disabled: false
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
			this.$refs.status.init();
		},
		hide() {
			this.options.visible = false;
		},
		login() {
			this.isLoading(true);

			let formData = new FormData();
			formData.append('username', this.username);
			formData.append('password', this.password);

			fetch(`${this.$store.state.api}/login`, {
				method: 'POST',
				credentials: 'include',
				body: (new URLSearchParams(formData))
			}).then(response => {
				if (response.status === 200) {
					this.$refs.status.setStatus('Success');
					this.$refs.status.setStatusMessage('Success! Redirecting...');
					setTimeout(() => {this.options.visible = false},1000);
					this.isLoading(false);
					this.$emit('loggedin');
				} else {
					this.isLoading(false);
					throw response;
				}
			}).catch((error) => {
				console.error(`${error.status}: ${error.statusText}`);
				this.$refs.status.setStatus('Fail');
				this.$refs.status.setStatusMessage(error.statusText);
			})
		},
		isLoading(value = true) {
			if(value) {
				this.$refs.status.init();
				this.options.disabled = true;
				this.loading = true;
			} else if(!value) {
				this.options.disabled = false;
			}
		}
	}
}
</script>