<template>
    <div>
      <div class="text-center pb-4">
        <span class="text-besar text-tebal">Status Pembayaran</span>
      </div>
      <b-card>
        <div slot="header">
          <span>Detail Pembayaran</span>
        </div>
        <b-row>
          <b-col cols="4">
            <span>Total Tagihan</span>
          </b-col>
          <b-col cols="8">
            <span>{{details.gross_amount | formatRupiah}}</span>
          </b-col>
          <b-col cols="4">
            <span>Tipe Pembayaran</span>
          </b-col>
          <b-col cols="8">
            <span>{{details.payment_type}}</span>
          </b-col>
          <b-col cols="12" v-if="details.va_numbers">
            <b-row>
              <b-col cols="4">
                <span>Nama Bank</span>
              </b-col>
              <b-col cols="8">
                <span>{{details.va_numbers[0].bank}}</span>
              </b-col>
            </b-row>
            <b-row>
              <b-col cols="4">
                <span>Virtual Akun</span>
              </b-col>
              <b-col cols="8">
                <span>{{details.va_numbers[0].va_number}}</span>
              </b-col>
            </b-row>
          </b-col>
          <b-col cols="4">
            <span>Tanggal Transaksi</span>
          </b-col>
          <b-col cols="8">
            <span>{{details.transaction_time | moment("dddd, Do MMMM YYYY, H:mm")}}</span>
          </b-col>
          <b-col cols="12" v-if="details.transaction_status == 'pending'">
            <b-row>
              <b-col cols="4">
                <span>Bayar Sebelum</span>
              </b-col>
              <b-col cols="8">
                <span>
                {{details.transaction_time | moment("add", "3 hours", "dddd, Do MMMM YYYY, H:mm")}}</span>
              </b-col>
            </b-row>
          </b-col>
          <b-col cols="4">
            <span>Status Pembayaran</span>
          </b-col>
          <b-col cols="8">
            <span v-if="details.transaction_status == 'settlement'">Sudah dibayar</span>
            <div v-if="details.transaction_status == 'pending'">Menunggu pembayaran diterima
            </div>
            
            <div class="pt-3" v-if="details.transaction_status">
              <router-link :to="'/purchase/seller-processed'">
                <button type="button" class="btn btn-outline-primary" v-if="details.transaction_status == 'settlement'">Selesai</button>
              </router-link>
              <router-link :to="'/purchase/'+details.order_id">
              <button type="button" class="btn btn-outline-primary" v-if="details.transaction_status == 'pending'">Lihat transaksi</button>
              </router-link>
            </div>
          </b-col>
        </b-row>
      </b-card>
    </div>
</template>
<script>
import helper from '../../services/helper'
export default {
  props: [
  ],
  data() {
    return {
      paymentForm : {
        orderId : this.$route.query.order_id,
        statusCode : this.$route.query.status_code,
        transactionStatus : '',
      },
      details : {}
    }
  },
  mounted() {
    this.getStatus();
  },
  methods: {
    getStatus(){
      axios.get('/api/user/payments/INV-'+this.paymentForm.orderId)
      .then(res => {
        this.details = res.data
      })
      .catch(err => {
        console.error(err); 
      })
    },
  },
  filters : {
    formatRupiah(value){
        return helper.formatRupiah(value);
      },
    }
  }
</script>