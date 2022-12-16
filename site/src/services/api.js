import axios from 'axios';

function cakeApi() {
	const cakeApiUrl = "/";
    const cakeApi = axios.create({
        baseURL: cakeApiUrl,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    });
    return cakeApi;
}

export {
	cakeApi
}