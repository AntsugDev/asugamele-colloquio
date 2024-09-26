import {createRouter, createWebHistory} from "vue-router";
import LoginPage from "../view/LoginPage.vue";
import HomePage from "../view/HomePage.vue";
import {store} from "../store/index.js";
import TablePage from "../view/TablePage.vue";

export const route = createRouter({
    history:createWebHistory(),
    routes:[
        {
            path:'/login',
            name:'Login',
            component: LoginPage
        },
        {
            path:'',
            name:'Home',
            component: HomePage,
            beforeEnter:(to,from,next) => {
                let token = store.getters['Auth/getToken']
                if(token === null)
                    next({name:'Login'})
                else
                    next();
            },
            children: [
                {
                    path:'/table',
                    name:'Table',
                    component: TablePage
                },
            ]
        }

    ]
});
