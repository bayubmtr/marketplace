import Vue from 'vue'
window.Vue = Vue;
import VueRouter from 'vue-router'
//helper
import helper from './services/helper'

// Containers
import Full from './containers/Full'

// Views
import Dashboard from './views/Dashboard'

// Views - Components
import UserList from './views/user/UserList'
import ProductList from './views/product/index'
import TransactionList from './views/transaction/index'
import Promo from './views/web/promo/index'
import Category from './views/web/category/index'
import StoreList from './views/store/index'
import Withdraw from './views/withdraw/index'
import Courier from './views/web/courier/index'
import Address from './views/web/address/index'
import Admin from './views/web/admin/index'

// Views - Pages
import Page404 from './views/pages/Page404'
import Page500 from './views/pages/Page500'
import Login from './views/pages/Login'
import Register from './views/pages/Register'


let routes = [
    {
      path: '/admin',
      component: Full,
      meta: { requiresAuth: true },
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'users',
          redirect: '/admin/users',
          name: 'user',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '/admin/users',
              name: 'User List',
              component: UserList
            },
          ]
        },
        {
          path: 'products',
          redirect: '/admin/products',
          name: 'product',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '/admin/products',
              name: 'Product List',
              component: ProductList
            }
          ]
        },
        {
          path: 'transactions',
          redirect: '/admin/transactions',
          name: 'Transaction',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '/admin/transactions',
              name: 'transaction List',
              component: TransactionList
            }
          ]
        },
        {
          path: '/admin/withdraws',
          name: 'WithDraw',
          component: Withdraw
        },
        {
          path: '/admin/stores',
          name: 'stores',
          component: StoreList
        },
        {
          path: '/admin/manage/promos',
          name: 'promo',
          component: Promo
        },
        {
          path: '/admin/manage/categories',
          name: 'category',
          component: Category
        },
        {
          path: '/admin/manage/logistics',
          name: 'courier',
          component: Courier
        },
        {
          path: '/admin/manage/administrative',
          name: 'address',
          component: Address
        },
        {
          path: '/admin/manage/admin',
          meta: { requiresManager: true },
          name: 'admin',
          component: Admin
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
    path: '/admin/auth',
    redirect: '/admin/auth/login',
    component: {
      render (c) { return c('router-view') }
    },
    meta: { requiresGuest: true },
    children: [
        {
            path: '/admin/auth/login',
            component: require('./views/auth/login')
        },
        {
            path: '/admin/auth/register',
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
    if (to.matched.some(m => m.meta.requiresManager)){
      return helper.checkManager().then(response => {
        if(!response){
          toastr.error('Anda dilarang mengakses halaman ini')
            return next({ path : '/admin/dashboard'})
        }

        return next()
    })
    }
    if (to.matched.some(m => m.meta.requiresAuth)){
        return helper.check().then(response => {
            if(!response){
                return next({ path : '/admin/auth/login'})
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
