<template>
    <b-modal id="saleReject" title="Tolak Pesanan" no-close-on-backdrop hide-footer centered>
        <b-row>
            <b-col cols="3">
                Alasan
            </b-col>
            <b-col cols="9">
                <b-form-textarea
                id="textarea"
                v-model="reason"
                placeholder="Tulis Alasan Menolak"
                rows="3"
                max-rows="5"
                ></b-form-textarea>
                <b-button :disabled="reason === ''" v-on:click="rejectOrder()" class="float-right mt-2" variant="outline-danger">Tolak Pesanan</b-button>
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
  name: 'saleReject',
  components: {
      
  },
  data () {
    return {
        reason : ''
    }
  },
  mounted() {
  },
  methods: {
    rejectOrder(){
        axios.patch('/api/user/seller/sales/'+this.order_id,{type : 'reject', reason : this.reason})
        .then(response => {
            toastr.success('Berhasil Menolak Pesanan')
            this.$bvModal.hide('saleReject');
            this.$emit('getOrder')
        })
        .catch(err => {
            if(err.response.data.errors.reason){
                toastr.error('Tulis Alasan Menolak Pesanan !')
            }else{
                toastr.error('Gagal Memproses Permintaan !')
            }
        })
    },
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