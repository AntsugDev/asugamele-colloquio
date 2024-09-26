import dayjs from "dayjs";

const state = () => ({
    data:{
        token:null,
        refresh_token:null,
        expired:null,
        login:null,
    },
})
const getters = {
    getToken:function(state){return state.data},
    getInit:function (state){
        let now = dayjs();
        if(state.data.login !== null){
            return parseInt(state.data.login.diff(now,'hours')) > 2
        }
    },
    getRefreshToken:function (state){
        return state.data.refresh_token
    }
}

const actions = {}
const mutations = {
    update (state,payload){
        state.data = payload
    },

}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
