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
              token : this.$route.params.token
         }
      }
    },
    mounted() {
        this.Activate();
    },
    methods: {
       Activate(){
           axios.post('/api/user/activate',this.tokenForm)
           .then(res => {
               toastr['success']("Akun anda berhasil diverifikasi");
           })
           .catch(err => {
               toastr['error']("Terjadi masalah !");
           })
        }
    },
}
</script>