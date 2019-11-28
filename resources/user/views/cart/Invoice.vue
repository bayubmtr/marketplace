<template>
        <b-card>
          <div slot="header" class="pl-2">
            <span>Ringkasan Belanja</span>
          </div>
          <div>
            <span>Total Harga Produk</span>
            <span class="float-right text-tebal">{{totalPrice | formatRupiah }}</span>
           <div v-if="promo_obtain">
              <span>Potongan promo</span>
              <span class="float-right text-tebal">{{promo_obtain | formatRupiah }}</span>
           </div>
            <div class="pt-2" v-if="totalShippingCost != 0">
              <span>Biaya Kirim</span>
              <span class="float-right text-tebal">{{totalShippingCost | formatRupiah }}</span>
            </div>
            <hr>
            <div v-if="ButtonPayDisable == false">
              <span>Total Tagihan</span>
              <span class="float-right text-tebal text-danger">{{updateTotalPrice | formatRupiah }}</span>
            </div>
            <div v-if="promo_active">
              <span>
                Punya Kode Promo / Kupon ?
              </span>
              <div>
                <input v-model="promo" type="text" :class="'form-control '+promo_css" id="validationServer03" placeholder="masukan kode" required>
                <div class="invalid-feedback">
                  Kode promo tidak berlaku
                </div>
              </div>
            </div>
            <div class="text-center" v-if="ButtonPay == true">
              <button :disabled="ButtonBuyDisable" v-on:click="buy" type="button" class="btn btn-danger mt-3">Lanjut</button>
            </div>
            <div class="text-center" v-if="ButtonPay == false">
              <button id="pay-button" :disabled="ButtonPayDisable" v-on:click="pay" type="button" class="btn btn-danger mt-3">Lanjut Pembayaran</button>
            </div>
          </div>
        </b-card>
</template>
<script>
import _ from 'lodash';
import helper from '../../services/helper'
export default {
  props: [
    'cart',
    'totalPrice',
    'tempSelectedProduct',
    'selectedProduct',
    'ButtonBuyDisable',
    'ButtonPayDisable',
    'totalShippingCost',
    'selectedAddress'
  ],
   data: function () {
    return {
      ButtonPay : true,
      promo_active : false,
      promo : '',
      promo_obtain : 0,
      snapToken : '',
      payForm : {
        shippingCost : 0,
        shippingAddress : '',
        productSelected : []
      }
    }
   },
  mounted() {
    if (this.$route.path == '/cart/shipment') {
         this.ButtonPay = false;
         this.promo_active = true;
    }
    else if(this.$route.path == '/cart') {
        this.ButtonPay = true;
    }
  },
  watch: {
      '$route' (to, from) {
      if (this.$route.path == '/cart/shipment') {
         this.ButtonPay = false;
         this.promo_active = true;
      }
      else if(this.$route.path == '/cart') {
         this.ButtonPay = true;
      }
    },
    promo :{deep: true,
      handler: _.debounce(function (val) {
        axios.patch('/api/user/carts/'+this.promo,{req_type : 'check_promo'})
        .then(res => {
          this.promo_obtain = res.data
        })
        .catch(err => {
          console.error(err); 
        })
      },1000)
    }
    },
  methods: {
    buy(){
      var checkout = this.$session.flash.get('selectedCartTemp')
      axios.patch('/api/user/carts/'+checkout,{req_type : 'checkout'})
      .then(res => {
        this.$emit('getSelectedCart');
        this.$router.push({
          path: '/cart/shipment'
        });
      })
      .catch(err => {
        console.error(err); 
      })
    },
    pay(){
      axios.post('/api/user/purchases',{reqType : 'newOrder'})
      .then(res => {
        console.log(res)
        this.$router.push({
        path: '/purchase/wait-payment'
      });
      })
      .catch(err => {
        console.error(err); 
      })
    },
  },
  computed: {
    updateTotalPrice(){
      return this.totalPrice+this.totalShippingCost-this.promo_obtain;
    },
    promo_css(){
      if(this.promo_obtain){
        return 'is-valid'
      }else{
        return 'is-invalid'
      }
    }
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    }
  }
}
</script>