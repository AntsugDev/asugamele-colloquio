import {createRouter, createWebHistory} from "vue-router";
import LoginPage from "../view/LoginPage.vue";
import HomePage from "../view/HomePage.vue";
import {store} from "../store/index.js";
import TablePage from "../view/TablePage.vue";
import dayjs from "dayjs";
import UserPage from "../view/UserPage.vue";
import ErrorPage from "../view/ErrorPage.vue";

export const route = createRouter({
    history:createWebHistory(),
    routes:[
        {
            path:'/login',
            name:'Login',
            component: LoginPage
        },
        {
            path:'/error',
            name:'Error',
            component: ErrorPage
        },
        {
            path:'',
            name:'Home',
            component: HomePage,
            beforeEnter:(to,from,next) => {
                let token = store.getters['Auth/getToken']
                if(token === null)
                    next({name:'Login'})
                else {
                    let expired = dayjs(token.expired);
                    let now = dayjs();
                    if(parseFloat(expired.diff(now,'minute')) > 0 ){
                        next();
                    }else
                        next({name:'Login',query: {error: btoa('Session expired')}})
                }
            },
            children: [
                {
                    path:'/table',
                    name:'Table',
                    component: TablePage
                },
                {
                    path:'/user',
                    name:'User',
                    component: UserPage
                },
            ]
        }

    ]
});
