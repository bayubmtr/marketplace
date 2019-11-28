<template>
    <b-modal id="saleSend" title="Kirim Pesanan" no-close-on-backdrop hide-footer centered>
        <b-row>
            <b-col cols="3">
                Nomor Resi
            </b-col>
            <b-col cols="9">
                <b-form-textarea
                id="text"
                v-model="tracking_number"
                placeholder="Tulis Nomor RESI"
                ></b-form-textarea>
                <b-button :disabled="tracking_number === ''" v-on:click="sendOrder()" class="float-right mt-2" variant="outline-primary">Kirim Pesanan</b-button>
            </b-col>
        </b-row>
    </b-modal>
</template>
<script>
import helper from '../../../services/helper'
export default {
  props: [
    'order_id'
  ],
  name: 'saleSend',
  components: {
      
  },
  data () {
    return {
        tracking_number : ''
    }
  },
  mounted() {

  },
  methods: {
    sendOrder(id, tracking_number){
        axios.patch('/api/user/seller/sales/'+this.order_id,{type : 'send', tracking_number : this.tracking_number})
        .then(response => {
            toastr.success('Berhasil Mengirim Pesanan')
            this.$bvModal.hide('saleSend');
            this.$emit('getOrder')
        })
        .catch(err => {
            if(err.response.data.errors.tracking_number){
                toastr.error('Resi Pengiriman Dibutuhkan !')
            }else{
                toastr.error('Gagal Memproses Permintaan !')
            }
        })
    }
  },
  computed: {
  },
  filters: {
    formatDate(date) {
      return helper.formatDate(date);
    },
    formatRupiah(value){
      return helper.formatRupiah(value);
    },
    Slug(value){
      return helper.toSlug(value);
    }
  },
  watch: {
  },
}
</script>