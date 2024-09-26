import {createStore} from "vuex";
import auth from "./modules/auth.js";
import Dialog from "./modules/Dialog.js";
import user from "./modules/user.js";

export const store =createStore({
    modules:{
        Auth:auth,
        Dialog:Dialog,
        User:user
    }
})
