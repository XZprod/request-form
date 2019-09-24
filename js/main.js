import FormComponent from './FormComponent.js';

Vue.component('form-component', FormComponent);

window.applic = new Vue({
    el: '#app',
    data: {
        message: 'Привет, Vue!',
    },
});