<template>
      <b-nav-item-dropdown right no-caret>
        <template slot="button-content">
          <img :src="getAvatar" class="img-avatar" alt="">{{getAuthUserFullName()}}
        </template>
        <b-dropdown-header tag="div" class="text-center"><strong>Account</strong></b-dropdown-header>
        <b-dropdown-item @click.prevent="logout"><i  class="fa fa-lock"></i> Logout</b-dropdown-item>
      </b-nav-item-dropdown>
</template>
<script>

    import helper from '../services/helper'

    export default {
      data() {
          return {
            altText : ''
          }
        },
        mounted(){
            axios.get('/api/admin/auth/user').then(response => response.data).then(response => {
                this.altText = response.user.email;
            });
        },
        methods : {
            logout(){
                helper.logout().then(() => {
                    this.$store.dispatch('resetAuthUserDetail');
                    this.$router.replace('/admin')
                })
            },
            getAuthUserFullName(){
                return this.$store.getters.getAuthUserFullName;
            },
            getAuthUser(name){
                return this.$store.getters.getAuthUser(name);
            }
        },
        computed: {
            getAvatar(){
                return this.getAuthUser('avatar');
            }
        }
    }
</script>
