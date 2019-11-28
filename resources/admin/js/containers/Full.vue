<template>
  <div class="app">
    <AppHeader/>
    <div class="app-body">
      <Sidebar :navItems="nav"/>
      <main class="main">
        <breadcrumb :list="list"/>
        <div class="container-fluid">
          <router-view></router-view>
        </div>
      </main>
    </div>
    <AppFooter/>
  </div>
</template>

<script>

import toastr from 'toastr'
import nav from '../_nav'
import helper from '../services/helper'
import { Header as AppHeader, Sidebar, Footer as AppFooter, Breadcrumb } from '../components/'

export default {
  name: 'full',
  components: {
    AppHeader,
    Sidebar,
    AppFooter,
    Breadcrumb,
    toastr
  },
  methods : {
      notification(){
          toastr.options = {
              "positionClass": "toast-top-right"
          };

          $('[data-toastr]').on('click',function(){
              var type = $(this).data('toastr'),message = $(this).data('message'),title = $(this).data('title');
              toastr[type](message, title);
          });
      },
      getAuthUser(name){
          return this.$store.getters.getAuthUser(name);
      }
  },
  data () {
    return {
      nav: nav.items
    }
  },
  computed: {
    name () {
      return this.$route.name
    },
    list () {
      return this.$route.matched
    }
  },
}
</script>
