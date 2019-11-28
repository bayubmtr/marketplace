<template>
    <div class="animated fadeIn">
        <div id="logreg-forms" v-if="emailSend == false">
            <div class="text-besar text-tebal text-center pt-3">Lupa Kata Sandi ?</div>
            <form @submit.prevent="submit" class="form-reset">
                <input type="email" v-model="emailForm.email" id="resetEmail" class="form-control" placeholder="Alamat Email" required="" autofocus="">
                <button class="btn btn-primary btn-block" type="submit">Atur ulang Kata Sandi</button>
                <a href="/auth/login" id="cancel_reset"><i class="fa fa-angle-left"></i> Kembali ke halaman Login</a>
            </form>
        </div>
        <b-card v-if="emailSend == true" class="text-center">
            Alamat penggantian kata sandi baru telah dikirim ke email anda !
        </b-card>
    </div>
</template>
<script>
export default {
    name : 'Login',
    data() {
      return {
         emailForm : {
              email : ''
         },
         emailSend : false
      }
    },
    methods: {
        submit(){
            axios.post('/api/user/request-reset-password',this.emailForm)
            .then(res => {
                this.emailSend = true;
                toastr['success']("Berhasil ! silahkan email anda.");
            })
            .catch(err => {
                toastr['error']("Terjadi masalah !");
            })
        }
    },
}
</script>