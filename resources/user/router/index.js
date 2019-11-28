import Vue from 'vue'
import Router from 'vue-router'
import helper from '../services/helper'

// Containers
import SelectContainer from '../containers/Container'

//auth
import Login from '../views/auth/Login'
import Activate from '../views/auth/Activate'
import Register from '../views/auth/Register'
import ForgotPassword from '../views/auth/ForgotPassword'
import ResetPassword from '../views/auth/ResetPassword'
import AuthSocial from '../views/auth/AuthSocial'

//promo
import Promos from '../views/pages/promos/Index'
import Promo from '../views/pages/promos/Promo'
// Views
import Dashboard from '../views/homepage/Homepage'
import StoreProfile from '../views/store/profile/Index'
import StoreProductDetail from '../views/store/product/ProductDetail'

//setting
import SettingIndex from '../views/setting/Index'
import SettingProfile from '../views/setting/store/Profile'
import SettingLogistic from '../views/setting/store/Logistic'
import SettingAddress from '../views/setting/Address'
import SettingUserWallet from '../views/setting/account/UserWallet'
import SettingPerformance from '../views/setting/store/Performance'
import SettingCreateStore from '../views/setting/store/CreateStore'
import SettingUserProfile from '../views/setting/account/UserProfile'
import SettingUserBank from '../views/setting/account/UserBank'
import SettingUserAccount from '../views/setting/account/UserAccount'
import sellerProductIndex from '../views/seller/Index'
import SellerProduct from '../views/seller/Product'
import SellerProductNew from '../views/seller/ProductNew'
import SellerProductEdit from '../views/seller/ProductEdit'
import SellerProductDiscussion from '../views/seller/ProductDiscussion'

import ProductSearch from '../views/product/index'

//message
import MessageIndex from '../views/message/Message'

//cart
import CartIndex from '../views/cart/Index'
import CartShipment from '../views/cart/shipment'
import CartProduct from '../views/cart/cart'

//payment
import PaymentIndex from '../views/payment/paymentIndex'
import PaymentStatus from '../views/payment/paymentStatus'
import PaymentUnfinish from '../views/payment/paymentUnfinish'
import PaymentError from '../views/payment/paymentError'

//Purchase
import PurchaseIndex from '../views/exchange/Index'
import PurchaseAll from '../views/exchange/purchase'
import PurchaseWaitPayment from '../views/exchange/purchaseWaitPayment'
import PurchaseSellerProcessed from '../views/exchange/purchaseSellerProcessed'
import PurchaseShipping from '../views/exchange/purchaseShipping'
import PurchaseFinish from '../views/exchange/purchaseFinish'
import OrderDetail from '../views/exchange/orderDetail'

//Sale
//Purchase
import SaleIndex from '../views/exchange/Index'
import SaleAll from '../views/exchange/sale'
import SaleNew from '../views/exchange/saleNew'
import SaleToShip from '../views/exchange/saleToShip'
import SaleShipping from '../views/exchange/saleShipping'
import SaleFinish from '../views/exchange/saleFinish'

//following
import FollowingStore from '../views/following/store'
import FavoriteProduct from '../views/following/favorite_product'


// Views - Pages
import Page404 from '../views/pages/Page404'
import Page500 from '../views/pages/Page500'


Vue.use(Router)


  let routes = [
    {
      path: '/',
      component: SelectContainer,
      children: [
    {
      path: '/',
      meta: { requiresGuest: true },
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '/',
          name: '',
          component: Dashboard
        },
        {
          path: '/auth/login',
          name: 'Login',
          component: Login
        },
        {
          path: '/auth/register',
          name: 'Register',
          component: Register
        },
        {
          path: '/auth/forgot-password',
          name: 'ForgotPassword',
          component: ForgotPassword
        },
        {
          path: '/auth/activate/:token',
          name: 'Activate',
          component: Activate
        },
        {
          path: '/auth/reset-password/:token',
          name: 'ResetPassword',
          component: ResetPassword
        },
        {
          path: '/auth/social',
          name: 'AuthSocial',
          component: AuthSocial
        },
      ]
    },
    {
      path: '/home',
      meta: { requiresAuth: true, showProgressBar: false },
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '/',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: '/cart',
          name: 'CartIndex',
          component: CartIndex,
          children: [ 
            {
              path: '/cart',
              name: 'CartProduct',
              component: CartProduct,
              meta: { title: 'Keranjang Belanja' }
            },
            {
              path: '/cart/shipment',
              name: 'CartShipment',
              component: CartShipment,
              meta: { title: 'Keranjang - Data Pengiriman' }
            },
          ]
        },
        {
          path: '/payment',
          name: 'PaymentIndex',
          component: PaymentIndex,
          children: [
            {
              path: '/payment/status',
              name: 'PaymentStatus',
              component: PaymentStatus
            },
            {
              path: '/payment/unfinish',
              name: 'PaymentUnfinish',
              component: PaymentUnfinish
            },
            {
              path: '/payment/error',
              name: 'PaymentError',
              component: PaymentError
            },
          ]
        },
        {
          path: '/message',
          name: 'MessageIndex',
          component: MessageIndex
        },
        {
          path: '/setting',
          name: 'seller',
          component: SettingIndex,
          children: [
            {
              path: '/setting/store/profile',
              meta: { requiresSeller: true },
              name: 'store_profile',
              component: SettingProfile
            },
            {
              path: '/setting/store/add',
              name: 'add_profile',
              component: SettingCreateStore
            },
            {
              path: '/setting/store/logistic',
              meta: { requiresSeller: true },
              name: 'storelogistic',
              component: SettingLogistic
            },
            {
              path: '/setting/address',
              name: 'address',
              component: SettingAddress
            },
            {
              path: '/setting/store/performance',
              meta: { requiresSeller: true },
              name: 'storeperformance',
              component: SettingPerformance
            },
            {
              path: '/setting/user-profile',
              name: 'account',
              component: SettingUserProfile
            },
            {
              path: '/setting/user-account',
              name: 'password',
              component: SettingUserAccount
            },
            {
              path: '/setting/user-bank',
              name: 'bank',
              component: SettingUserBank
            },
            {
              path: '/setting/user-wallet',
              name: 'wallet',
              component: SettingUserWallet
            },
          ]
        },
        {
          path: '/seller',
          name: 'productadd',
          meta : { requiresSeller: true },
          component: sellerProductIndex,
          children: [
            {
              path: '/seller/product/discussion',
              name: 'seller-product-discussion',
              component: SellerProductDiscussion
            },
            {
              path: '/seller/product/new',
              name: 'seller-product-new',
              component: SellerProductNew
            },
            {
              path: '/seller/product',
              name: 'seller-product',
              component: SellerProduct
            },
            {
              path: '/seller/product/:product_id',
              name: 'seller-product-edit',
              component: SellerProductEdit
            },
          ]
        },
        {
          path: '/purchase',
          name: 'purchase',
          component: PurchaseIndex,
          redirect: '/purchase',
          children: [
            {
              path: '/purchase',
              name: 'purchase-all',
              component: PurchaseAll
            },
            {
              path: '/purchase/wait-payment',
              name: 'purchase-wait-payment',
              component: PurchaseWaitPayment
            },
            {
              path: '/purchase/seller-processed',
              name: 'purchase-seller-processed',
              component: PurchaseSellerProcessed
            },
            {
              path: '/purchase/shipping',
              name: 'purchase-shipping',
              component: PurchaseShipping
            },
            {
              path: '/purchase/finish',
              name: 'purchase-finish',
              component: PurchaseFinish
            },
            {
              path: '/purchase/:orderId',
              name: 'purchase-detail',
              component: OrderDetail
            }
          ]
        },
        {
          path: '/sale',
          meta: { requiresSeller: true },
          name: 'sale',
          component: SaleIndex,
          redirect: '/sale',
          children: [
            {
              path: '/sale',
              name: 'sale-all',
              component: SaleAll
            },
            {
              path: '/sale/new',
              name: 'sale-new',
              component: SaleNew
            },
            {
              path: '/sale/to-ship',
              name: 'sale-to-ship',
              component: SaleToShip
            },
            {
              path: '/sale/shipping',
              name: 'sale-shipping',
              component: SaleShipping
            },
            {
              path: '/sale/finish',
              name: 'sale-finish',
              component: SaleFinish
            },
          ]
        },
        {
          path: '/following',
          name: 'FollowingStore',
          component: FollowingStore
        },
        {
          path: '/favorite_product',
          name: 'FavoriteProduct',
          component: FavoriteProduct
        },
        {
          path: '',
          redirect: '',
          children: [
            
          ]
        },
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
      ]
    },
    {
      path: '/product',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '/products',
          name: 'product-search',
          component: ProductSearch
        }
      ]
    },
    {
      path: '/promo',
      redirect: '/promo',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '/promo',
          name: 'promos',
          component: Promos
        },
        {
          path: '/promo/:promo_id',
          name: 'promo',
          component: Promo
        }
      ]
    },
    {
      path: '/',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '/:store_url',
          name: 'storeprofile',
          component: StoreProfile
        },
        {
          path: '/:store_url/:product_id/:product_name',
          name: 'storeproductdetail',
          component: StoreProductDetail
        },
      ]
    },
      ]
    }
  ]

const router = new Router({
	routes,
    linkActiveClass: 'active',
    mode: 'history'
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(m => m.meta.requiresSeller)){
    return helper.checkSeller().then(response => {
      if(!response){
        toastr.error('Anda belum memiliki toko !')
        return next({ path : '/setting/store/add'})
      }
      return next()
    })
    next()
  }
  if (to.matched.some(m => m.meta.requiresAuth)){
      return helper.check().then(response => {
          if(!response){
            toastr.error('Anda belum login !')
            return next({ path : '/'})
          }
          return next()
      })
  }

  if (to.matched.some(m => m.meta.requiresGuest)){
      return helper.check().then(response => {
          if(response){
              return next({ path : '/home'})
          }
          return next()
      })
  }

  
  return next()
})
router.beforeResolve((to, from, next) => {
  if (to.name) {
      NProgress.start()
  }
  next()
})
const DEFAULT_TITLE = 'TokoLan';
router.afterEach((to, from) => {
  NProgress.done();
  document.title = to.meta.title || DEFAULT_TITLE;
})

export default router;