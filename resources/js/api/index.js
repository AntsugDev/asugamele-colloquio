import axios from "axios";
import {store} from "../store/index.js";
export const api = (url,method,payload = null,queryString= null) => {

    if(queryString !== null)
        url +='?'+queryString;

    let header = {
        "Content-Type": "application/json",
        Accept: "application/json",
    }
    let token =store.getters['Auth/getToken'];
    if(url.toString().indexOf('list') !== -1 && token !== null) {
        header.Authorization = `Bearer ${token}`
    }

    let config = {
        baseURL: 'http://localhost:8000/api/',
        url: url,
        method: method,
        headers: header,
        timeout: 180000,
        data: payload !== null ? payload : undefined,
    }
    return axios.request(config)
}
