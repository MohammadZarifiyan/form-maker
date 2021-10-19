import axios from "axios";

axios.defaults.baseURL = 'http://localhost/back-end/api/';
axios.defaults.withCredentials = true;

export default axios;
