<template>
    <div class="animated fadeIn">
        <b-card class="text-center">
            Email anda sudah terverifikasi
        </b-card>
    </div>
</template>
<script>
export default {
    name : 'Activate',
    data() {
      return {
         tokenForm : {
              token : this.$route.query.token
         }
      }
    },
    mounted() {
       axios.post('/api/user/social', this.tokenForm).then(response => {
                localStorage.setItem('auth_token',response.data.token);
                axios.defaults.headers.common['Authorization'] = 'Bearer ' + localStorage.getItem('auth_token');
                toastr['success'](response.data.message);
                location.reload();
            }).catch(error => {
                location.reload();
            });
    },
    methods: {
    },
}
</script>