import {createVuetify} from "vuetify";
import 'vuetify/dist/vuetify.min.css';
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import { it } from 'vuetify/locale';

const custTheme = {
    dark: false,
    colors:
        {
            white: '#FFFFFF',
            primary: '#00bcd4',
            secondary: '#2196f3',
            accent: '#3f51b5',
            error: '#ff5722',
            warning: '#ffc107',
            info: '#607d8b',
            success: '#009688',
            "base-color":"#00bcd4",
            "background": "#00bcd4",
             text:"#99a3a4"
        }

}

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "custTheme",
        themes: {custTheme}
    },
    lang: {
        locales: { it },
        current: 'it',
    },
});
export default vuetify;