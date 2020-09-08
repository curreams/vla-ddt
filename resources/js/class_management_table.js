
window.Vue = require('vue');


Vue.component('class-management-table', require('./components/ClassManagementTable.vue').default);


const app = new Vue({
    el: '#classTable',
});
