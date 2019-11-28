export default {
  items: [
    {
      name: 'Dashboard',
      url: '/admin/dashboard',
      icon: 'icon-speedometer',
    },
    {
      title: true,
      name: 'General',
      class: '',
      wrapper: {
        element: '',
        attributes: {}
      }
    },
    {
      name: 'User',
      url: '/admin/users',
      icon: 'icon-user'
    },
    {
      name: 'Product',
      url: '/admin/products',
      icon: 'fa fa-database'
    },
    {
      name: 'Transaction',
      url: '/admin/transactions',
      icon: 'fa fa-exchange'
    },
    {
      name: 'Store',
      url: '/admin/stores',
      icon: 'fa fa-building'
    },
    {
      name: 'Withdraw',
      url: '/admin/withdraws',
      icon: 'fa fa-dollar'
    },
    {
      title: true,
      name: 'MANAGE',
      class: '',
      wrapper: {
        element: '',
        attributes: {}
      }
    },
    {
      name: 'Promo',
      url: '/admin/manage/promos',
      icon: 'fa fa-bullhorn'
    },
    {
      name: 'Category',
      url: '/admin/manage/categories',
      icon: 'fa fa-tag'
    },
    {
      name: 'Logistic',
      url: '/admin/manage/logistics',
      icon: 'fa fa-truck'
    },
    {
      name: 'Administrative',
      url: '/admin/manage/administrative',
      icon: 'fa fa-address-card'
    },
    {
      name: 'Admin',
      url: '/admin/manage/admin',
      icon: 'fa fa-user'
    }
  ]
}
