
require('./bootstrap');

window.Vue = require('vue');

// Auto scroll
import Vue from 'vue'
import VueChatScroll from "vue-chat-scroll";
Vue.use(VueChatScroll);

Vue.component('chat-component', require('./components/ChatComponent.vue').default);





const app = new Vue({
    el: '#chat-component',
});
