<template>
  <div class="animated fadeIn">
    <b-card v-show="checkCart == 2" class="text-center text-besar">
      <strong>Belum ada produk di keranjang Anda</strong>
    </b-card>
    <b-row v-show="checkCart == 1">
      <b-col cols="7">
        <router-view 
          :selectedCart="selectedCart" 
          :cart="carts" 
          v-on:totalPrice="updateTotalPrice($event)" 
          v-on:updateTotalShippingCost="updateTotalShippingCost($event)" 
          v-on:updateTotalShippingCount="updateButtonPayDisable($event)" 
          v-on:selectedCartTemp="updateSelectedProduct($event)"
          v-on:selectedAddress="updateSelectedAddress($event)"
          v-on:getSelectedCart="getSelectedCart()"
          v-on:updateCart="getCart">
        </router-view>
      </b-col>
      <b-col cols="5">
        <invoice  
          :totalShippingCost="totalShippingCost" 
          :ButtonPayDisable="ButtonPayDisable" 
          :ButtonBuyDisable="ButtonBuyDisable" 
          :tempSelectedProduct="selectedCartTemp" 
          :selectedAddress="selectedAddress" 
          :totalPrice="setTotalPrice" 
          :cart="carts" 
          v-on:getSelectedCart="getSelectedCart()">
        </invoice>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import invoice from './invoice'
import cart from './cart'
import shipment from './shipment'
export default {
  name: 'index',
  components: {
    invoice,
    cart,
    shipment
  },
  data: function () {
    return {
      carts : {},
      selectedAddress : '',
      selectedCart : [],
      selectedCartTemp : [],
      totalPrice : 0,
      ButtonPayDisable : true,
      ButtonBuyDisable : true,
      totalShippingCost : 0,
      checkCart : 0
    }
  },
  mounted() {
    this.getCart();
  },
  methods: {
    getCart() {
      axios.get('/api/user/carts')
      .then(res => {
        if(res.status == 200){
          this.carts = res.data
          this.checkCart = 1
        }else{
          this.checkCart = 2
        }
      })
      .catch(err => {
      })
    },
    getSelectedCart(){
      axios.get('/api/user/carts?type=checkout')
      .then(res => {
        this.selectedCart = res.data
      })
      .catch(err => {
        this.$router.push("/carts")
      })
    },
    updateSelectedProduct: function(updatedProduct){
      this.selectedCartTemp = updatedProduct
      this.$session.flash.set('selectedCartTemp',this.selectedCartTemp)
    },
    updateTotalPrice: function(updatedTotalPrice){
      this.totalPrice = updatedTotalPrice
      if(this.totalPrice != 0){
        this.ButtonBuyDisable = false
      }else{
        this.ButtonBuyDisable = true
      }
    },
    updateTotalShippingCost: function(totalShippingCost){
      this.totalShippingCost =  totalShippingCost
    },
    updateButtonPayDisable: function(totalShippingCount){
      var osize = 0, key;
      for (key in this.selectedCart) {
          if (this.selectedCart.hasOwnProperty(key)) osize++;
      }
      if(osize == totalShippingCount){
        this.ButtonPayDisable = false;
      }else{
        this.ButtonPayDisable = true;
      }
    },
    updateSelectedAddress: function(address){
      this.selectedAddress = address
    }
  },
  computed: {
    setTotalPrice(){
      var totalPrice = 0;
      if(!this.totalPrice){
        if(this.$route.path != '/carts'){
        for(var i in this.selectedCart)
          for(var x in this.selectedCart[i])
            totalPrice = totalPrice + (this.selectedCart[i][x].product.price * this.selectedCart[i][x].quantity)
        }
      }
      else{
        totalPrice = this.totalPrice;
      }
      console.log(this.totalPrice)
        return totalPrice;
    }
  },
  watch: {
    '$route' (to, from) {
      this.getSelectedCart();
      this.getCart();
      this.totalPrice = 0;
    }
  },
}
</script>