import './bootstrap';
import {createApp} from "vue";
import App from "./App.vue";
import {route} from "./router/index.js";
import vuetify from "./theme/index.js";
import {store} from "./store/index.js";

const app = createApp(App);
app.use(route).use(vuetify).use(store);
app.mount('#app');
