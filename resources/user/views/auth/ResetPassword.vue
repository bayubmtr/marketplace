<template>
    <div class="animated fadeIn">
        <template v-if="resetPassword == false">
            <div>
                <div id="logreg-forms" v-if="verificationToken == true">
                    <div class="text-besar text-tebal text-center pt-3">Buat Kata Sandi Baru</div>
                    <form @submit.prevent="submit" class="form-reset">
                        <input type="password" v-model="passwordForm.password" id="password1" class="form-control" placeholder="Alamat Email" required="" autofocus="">
                        <input type="password" v-model="passwordForm.password_confirmation" id="password2" class="form-control" placeholder="Konfirmasi Alamat Email" required="" autofocus="">
                        <button v-on:click="reset()" class="btn btn-primary btn-block" type="submit">Atur ulang Kata Sandi</button>
                        <a href="/auth/login" id="cancel_reset"><i class="fa fa-angle-left"></i> Kembali ke halaman Login</a>
                    </form>
                </div>
                <b-card v-if="verificationToken == false" class="text-center">
                    Token salah atau sudah kadaluarsa !
                </b-card>
            </div>
        </template>
        <template v-if="resetPassword == true">
            <div>
                <b-card class="text-center">
                    Berhasil mengganti kata sandi baru. Silahkan login kembali. <a href="/auth/login" id="cancel_reset">Login</a>
                </b-card>
            </div>
        </template>
    </div>
</template>
<script>
export default {
    name : 'Login',
    data() {
      return {
         passwordForm : {
              email : '',
              password : '',
              password_confirmation : '',
              token : this.$route.params.token,
         },
         verificationToken : false,
         resetPassword : false
      }
    },
    mounted() {
        this.requestReset();
    },
    methods: {
        reset(){
            axios.post('/api/user/reset-password',this.passwordForm)
            .then(res => {
                toastr['success']("Kata sandi berhasil diganti");
                this.resetPassword = true;
            })
            .catch(err => {
                toastr['error']("Terjadi masalah !");
            })
        },
        requestReset(){
            axios.post('/api/user/validate-reset-password',this.passwordForm)
            .then(res => {
                this.verificationToken = true;
                this.passwordForm.email = res.data.email;
                toastr['success']("Silahkan masukan kata sandi baru");
            })
            .catch(err => {
                toastr['error']("Token tidak valid !");
            })
        }
    },
}
</script>