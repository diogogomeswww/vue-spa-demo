/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import {BootstrapVue, IconsPlugin} from 'bootstrap-vue'
import VueRouter from 'vue-router';
import routes from './routes';
import Notifications from 'vue-notification';
import Sidebar from './components/SidebarComponent';
import Login from './components/LoginComponent';
import NotFound from './components/NotFoundComponent';
import Fields from './components/Fields/FieldsComponent';
import Subscribers from './components/Subscribers/SubscribersComponent';

Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);
Vue.use(Notifications);

window.Event = new Vue();

const app = new Vue({
    el: '#app',
    router: new VueRouter(routes),
    components: {
        Sidebar, Login, NotFound, Fields, Subscribers,
    },
    data: {
        user: null,
        fields: [],
        subscribers: []
    },
    methods: {
        showLoginPage: function() {
            this.$router.push('/login').catch(err => {});
        },
        showApp: function() {
            if (this.$route.path === '/login') {
                this.$router.push('/fields').catch(err => {});
            }
        },
        loadData: function() {
            this.getAllFields();
            this.getAllSubscribers();
        },
        getToken: function() {
            return localStorage.getItem('_ct');
        },
        setToken: function(token) {
            if(token) {
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
                localStorage.setItem('_ct', token);
            } else {
                localStorage.removeItem('_ct');
            }
        },
        init: function() {
            return axios.get('/api/user')
                .then(r => {
                    app.user = r.data;
                    app.loadData();
                    app.showApp();
                })
                .catch(function(response){
                    console.log(response);
                    app.showLoginPage();
                });
        },
        getAllFields: function() {
            axios.get('/api/fields')
                .then(r => app.fields = r.data)
                .catch(console.error);
        },
        getAllSubscribers: function() {
            axios.get('/api/subscribers')
                .then(r => app.subscribers = r.data)
                .catch(console.error);
        },
        bindEvents: function() {
            // Auth
            Event.$on('user:auth', function (data) {
                app.setToken(data.token);
                app.user = data.user;
                app.loadData();
                app.showApp();
                this.$notify({text: `Welcome ${data.user.name}`});
            });
            Event.$on('user:logout', function (data) {
                app.setToken(null);
                app.user = null;
                app.showLoginPage();
            });
            // Fields
            Event.$on('field:updated', function (field) {
                app.fields.splice(_.findIndex(app.fields, {id: field.id}), 1, field)
                this.$notify({text: `Field ${field.title} updated.`});
            });
            Event.$on('field:new', function (field) {
                app.fields.push(field);
                this.$notify({text: `Field ${field.title} created.`});
            });
            Event.$on('field:deleted', function (field_id) {
                let index = _.findIndex(app.fields, {id: field_id});
                app.fields.splice(index, 1);
                this.$notify({text: `Field ${app.fields[index].title} deleted.`});
            });
            // Subscribers
            Event.$on('subscriber:updated', function (subscriber) {
                app.subscribers.splice(_.findIndex(app.subscribers, {id: subscriber.id}), 1, subscriber)
                this.$notify({text: `Subscriber ${subscriber.name} updated.`});
            });
            Event.$on('subscriber:new', function (subscriber) {
                app.subscribers.push(subscriber);
                this.$notify({text: `Subscriber ${subscriber.name} created.`});
            });
            Event.$on('subscriber:deleted', function (subscriber_id) {
                let index = _.findIndex(app.subscribers, {id: subscriber_id});
                app.subscribers.splice(index, 1);
                this.$notify({text: `Subscribers ${app.subscribers[index].title} deleted.`});
            });
        }
    },
    mounted: function() {
        const token = this.getToken();

        if (token) {
            this.setToken(token);
            this.init();
        } else {
            this.showLoginPage();
        }

        this.bindEvents();
    }
});
