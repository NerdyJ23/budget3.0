import { cakeApi } from "./api";

export default {
	login(username, password) {
		console.log(this.$store);
		let formData = new FormData();
		formData.append('username', username);
		formData.append('password', password);

		const response = cakeApi().post(`/login`, formData).catch((error) => {
			return error.response;
		});
		return response;
	},
	listReceipts(month, year) {
		const response = cakeApi().get(`/receipt?month=${month}&year=${year}`).catch((error) => {
			return error.response;
		});
		return response;
	},
	updateReceipt(payload) {
		let formData = new FormData();
		formData.append('receipt', JSON.stringify(payload));

		const response = cakeApi().patch(`/receipt/${payload.id}`, formData).catch((error) => {
			return error.response;
		});
		return response;
	},
	createReceipt(name, location, date, items) {
		let formData = new FormData();

		formData.append('name', name);
		formData.append('location', location);
		formData.append('date', date);
		formData.append('items', JSON.stringify(items));

		const response = cakeApi().put(`/receipt/`, formData).catch((error) => {
			return error.response;
		});
		return response;
	},
	deleteReceipt(id) {
		const response = cakeApi().delete(`/receipt/${id}`).catch((error) => {
			return error.response;
		});
		return response;
	}
}