<template>
    <div class="animated fadeIn">
        <b-row class="text-center">
            <span>Login</span>
        </b-row>
    </div>
</template>
<script>
import Login from './Login'
import Register from './Register'
import helper from '../../services/helper'
export default {
    components :{
        Login,
        Register
    }
}
</script>
      