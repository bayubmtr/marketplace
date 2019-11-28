import VueRouter from 'vue-router'
//helper
import helper from './services/helper'

// Containers
import Full from './containers/Full'

// Views
import Dashboard from './views/Dashboard'
import Charts from './views/Charts'
import Widgets from './views/Widgets'

// Views - Components
import Buttons from './views/components/Buttons'
import SocialButtons from './views/components/SocialButtons'
import Cards from './views/components/Cards'
import Forms from './views/components/Forms'
import Modals from './views/components/Modals'
import Switches from './views/components/Switches'
import Tables from './views/components/Tables'
import UserList from './views/user/UserList'
import ProductList from './views/product/index'


// Views - Icons
import FontAwesome from './views/icons/FontAwesome'
import SimpleLineIcons from './views/icons/SimpleLineIcons'

// Views - Pages
import Page404 from './views/pages/Page404'
import Page500 from './views/pages/Page500'
import Login from './views/pages/Login'
import Register from './views/pages/Register'


let routes = [
    {
      path: '/',
      component: Full,
      meta: { requiresAuth: true },
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'charts',
          name: 'Charts',
          component: Charts
        },
        {
          path: 'widgets',
          name: 'Widgets',
          component: Widgets
        },
        {
          path: 'components',
          redirect: '/components/buttons',
          name: 'Components',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'buttons',
              name: 'Buttons',
              component: Buttons
            },
            {
              path: 'social-buttons',
              name: 'Social Buttons',
              component: SocialButtons
            },
            {
              path: 'cards',
              name: 'Cards',
              component: Cards
            },
            {
              path: 'forms',
              name: 'Forms',
              component: Forms
            },
            {
              path: 'modals',
              name: 'Modals',
              component: Modals
            },
            {
              path: 'switches',
              name: 'Switches',
              component: Switches
            },
            {
              path: 'tables',
              name: 'Tables',
              component: Tables
            }
          ]
        },
        {
          path: 'user',
          redirect: '/user/userlist',
          name: 'user',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'userlist',
              name: 'User List',
              component: UserList
            }
          ]
        },
        {
          path: 'product',
          redirect: '/product/productlist',
          name: 'product',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'productlist',
              name: 'Product List',
              component: ProductList
            }
          ]
        },
        {
          path: 'icons',
          redirect: '/icons/font-awesome',
          name: 'Icons',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'font-awesome',
              name: 'Font Awesome',
              component: FontAwesome
            },
            {
              path: 'simple-line-icons',
              name: 'Simple Line Icons',
              component: SimpleLineIcons
            }
          ]
        }
      ]
    },
    {
      path: '/pages',
      redirect: '/pages/404',
      name: 'Pages',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '404',
          name: 'Page404',
          component: Page404
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500
        },
        {
          path: 'login',
          name: 'Login',
          component: Login
        },
        {
          path: 'register',
          name: 'Register',
          component: Register
        }
      ]
    },
    {
    path: '/admin',
    component: {
      render (c) { return c('router-view') }
    },
    meta: { requiresGuest: true },
    children: [
        {
            path: '/admin/login',
            component: require('./views/auth/login')
        },
        {
            path: '/admin/register',
            component: require('./views/auth/register')
        },
      ]
    }
  ];

const router = new VueRouter({
	routes,
    linkActiveClass: 'active',
    mode: 'history'
});

router.beforeEach((to, from, next) => {

    if (to.matched.some(m => m.meta.requiresAuth)){
        return helper.check().then(response => {
            if(!response){
                return next({ path : '/admin/login'})
            }

            return next()
        })
    }

    if (to.matched.some(m => m.meta.requiresGuest)){
        return helper.check().then(response => {
            if(response){
                return next({ path : '/admin'})
            }

            return next()
        })
    }

    return next()
});

export default router;
