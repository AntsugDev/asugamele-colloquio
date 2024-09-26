import {createStore} from "vuex";
import auth from "./modules/auth.js";
import Dialog from "./modules/Dialog.js";

export const store =createStore({
    modules:{
        Auth:auth,
        Dialog:Dialog
    }
})
