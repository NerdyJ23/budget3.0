<template>
<div>
    <v-dialog
		v-model="visible"
		max-width="75vw"
    >
        <v-card>
            <v-card-title>{{options.mode}} Receipt</v-card-title>

            <v-card-text>
				<v-form>
					<v-row>
						<v-col cols="8">
							<v-text-field
								v-model="receipt.name"
							></v-text-field>
						</v-col>
						<v-col cols="4">
							<v-text-field
							v-model="receipt.date"
							prepend-icon="mdi-calendar"
							@click="datePick = true"
							readonly
							></v-text-field>
						</v-col>
					</v-row>
				</v-form>
            </v-card-text>
			<v-card-actions>
				<v-btn @click="save">Save</v-btn>
				<v-btn color="red" @click="hide">Cancel</v-btn>
			</v-card-actions>
        </v-card>
    </v-dialog>
	<v-dialog v-model="datePick" width="50vw">
		<v-date-picker v-model="receipt.date" full-width>
			<template v-slot="{item}">
				<v-row>
					<v-col cols="12" class="d-flex align-end">
						<v-spacer></v-spacer>
						<v-btn class="px-2 mx-2" @click.stop="datePick = false">Save</v-btn>
					</v-col>
				</v-row>

			</template>

		</v-date-picker>
	</v-dialog>
</div>
</template>

<script>
export default {
	mounted() {
		this.init();
	},
    data: function () {
        return {
			visible:false,
			datePick: false,
			defaults: {
				modes: ['Add','Edit'],
			},
			receipt: {
				name: '',
				date: null,
				id: 0
			},
			receiptRef: {},
			options: {
				mode: ''
			}
        }
    },
	methods: {
		init() {
			// if(typeof this.r !== 'undefined') {
			// 	this.receipt = {...r};
			// } else {
			// 	this.options.mode = this.defaults.modes[0];
			// }
		},
		show() {
			this.visible = true;
		},
		hide() {
			this.visible = false;
			this.init();
		},
		save() {
			//ping to place
			let formData = new FormData();
			formData.append('_csrfToken', this.$store.state.csrfToken);
			formData.append('receipt', JSON.stringify(this.receipt));
			console.log(`before PATCH: ${this.$store.state.csrfToken}`);
			console.log(`${this.$store.state.api}/receipt/${this.receipt.id}`);
			fetch(`${this.$store.state.api}/receipt/${this.receipt.id}`, {
				method: 'PATCH',
				body: (new URLSearchParams(formData))
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