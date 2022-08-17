<template>
	<v-dialog
	v-model="options.visible"
	max-width="75vw"
	>
		<v-card>
			<v-card-title>{{options.mode}}</v-card-title>

			<v-card-text>
				<v-form ref="login" class="mx-4">
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
				</v-form>
			</v-card-text>

			<v-card-actions class="d-flex justify-end">
				<v-btn color="green lighten-2" @click="login">Login</v-btn>
				<v-btn outlined color="red">Cancel</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
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
				mode: '',
			}
		}
	},

	methods: {
		init() {
			this.options.mode = this.defaults.modes[0];
		},
		show() {
			this.options.visible = true;
		},
		hide() {
			this.options.visible = false;
		},
		login() {
			let formData = new FormData();
			formData.append('username', this.username);
			formData.append('password', this.password);

			fetch(`${this.$store.state.api}/login`, {
				method: 'POST',
				credentials: 'include',
				body: (new URLSearchParams(formData))
			}).then(response => {
				console.log('response');
				console.log(response);
				if (response.status === 200) {
					return response.json();
				} else {
					throw new Error();
				}
			}).then(data => {
				console.log('data');
				console.log(data);
			})
		}
	}
}
</script>