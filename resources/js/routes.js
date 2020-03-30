import NotFound from './components/NotFoundComponent';
import Login from './components/LoginComponent';
import Fields from './components/Fields/FieldsComponent';
import Subscribers from './components/Subscribers/SubscribersComponent';

export default {
    linkActiveClass: 'font-bold',

    routes: [
        {
            name: 'notfound',
            path: '*',
            component: NotFound
        },
        {
            name: 'login',
            path: '/login',
            component: Login
        },
        {
            name: 'fields',
            path: '/fields',
            component: Fields
        },
        {
            name: 'subscribers',
            path: '/subscribers',
            component: Subscribers
        }
    ]
}
