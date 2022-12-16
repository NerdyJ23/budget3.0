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
	}
}