<template>
    <div>
        <b-card-header class="border mb-3">
          <div class="pl-2">
            <input v-model="allSelected" ref="myBtn" type="checkbox" class="form-check-input" id="exampleCheck1">
            <span class="text-muted">Pilih Semua Produk</span>
            <span v-on:click="deleteSelectedProduct" class="mouse-pointer float-right">
              Hapus
            </span>
          </div>
        </b-card-header>
        <b-card v-for="(data, index) in cart" :key="index">
          <div slot="header" class="pl-2">
            <input v-on:change="select" v-model="selected_store" :value="data[0].product.store_id" type="checkbox" class="form-check-input" id="exampleCheck1"><span class="text-tebal"> <router-link class="text-dark" :to="'/'+data[0].product.store.store_url"> {{data[0].product.store.name}}</router-link></span>
          </div>
          <b-row v-for="item in data" :key="item.id">
            <b-col cols="1" class="pr-0 ml-2 text-left">
              <div class="align-middle m-0" style="display: inline-block;">
                <input v-on:click="select" v-model="selectedProduct" :value="item.id" type="checkbox" class="form-check-input" id="exampleCheck1">
                <img class="foto-product-thumb" :src="item.product.photo.small" alt="" style="max-height:59px!important">
              </div>
            </b-col>
            <b-col cols="9">
              <span class="text-tebal text-sedang">
                <router-link :to="'/'+item.product.store.store_url+'/'+item.product_id+'/'+Slug(item.product.name)">{{item.product.name}}</router-link>
              </span>
              <div class="text-sedang text-tebal detail-product-price">
                {{item.product.price*selectedCart[item.id].quantity | formatRupiah}}
              </div>
              <div class="input-group pb-3">
                <span class="input-group-btn border">
                    <button v-on:click=" setProductQty('-', item.id, item.product.stock)" type="button" class="btn btn-default fa fa-minus" data-type="minus" data-field="quant[1]">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
                <input v-on:change="setProductQty('x', item.id, item.product.stock)" v-if="selectedCart[item.id]" class="text-center" type="text" v-model="selectedCart[item.id].quantity" style="max-width:60px">
                <span class="input-group-btn border">
                    <button v-on:click=" setProductQty('+', item.id, item.product.stock)" type="button" class="btn btn-default fa fa-plus" data-type="plus" data-field="quant[1]">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
              </div>
            </b-col>
            <b-col cols="1" class="text-right">
              <span v-on:click="deleteProduct(item.id)" class="fa fa-trash fa-lg mouse-pointer"></span>
            </b-col>
          </b-row>
        </b-card>
    </div>
</template>

<script>
import _ from 'lodash';
import helper from '../../services/helper'
export default {
  props: [
    'cart'
  ],
  data() {
    return {
      selected_store : [],
      selectedProduct : [],
      allSelected: false,
      storeSelected: false,
      totalPrice : 0,
      selectedCart : [],
      selectedCartIndex : '',
      count : 0,
      
      }
  },
  mounted() {
  },
  methods: {
    select: function() {
            this.allSelected = false;
    },
    setProductQty(quantity, id, max_quantity){
      if(quantity == '+'){
        if(this.selectedCart[id].quantity > max_quantity){
          this.selectedCart[id].quantity =  max_quantity
          toastr.error('Stok produk hanya tersedia '+max_quantity)
        }else if(this.selectedCart[id].quantity < 1){
          this.selectedCart[id].quantity =  1
        }else{
          this.selectedCart[id].quantity = this.selectedCart[id].quantity + 1
          for(var i in this.selectedCart){
            if(this.selectedCart[i].updated == true){
              this.selectedCart[i].updated = false
            }
          }
          this.selectedCart[id].updated = true
        }
      }else if(quantity == '-'){
        if(this.selectedCart[id].quantity > 1){
          this.selectedCart[id].quantity = this.selectedCart[id].quantity - 1
          for(var i in this.selectedCart){
            if(this.selectedCart[i].updated == true){
              this.selectedCart[i].updated = false
            }
          }
          this.selectedCart[id].updated = true
        }
      }if(quantity == 'x'){
        if(this.selectedCart[id].quantity > max_quantity){
          this.selectedCart[id].quantity =  max_quantity
          toastr.error('Stok produk hanya tersedia '+max_quantity)
        }
        for(var i in this.selectedCart){
          if(this.selectedCart[i].updated == true){
            this.selectedCart[i].updated = false
          }
        }
        this.selectedCart[id].updated = true
      }
    },
    deleteProduct(id){
      let options = {
        okText: 'Hapus',
        cancelText: 'Batal',
        }
      this.$dialog
      .confirm('Hapus Produk ini ?', options)
      .then(xxx => {
        axios.delete('/api/user/carts/'+id)
        .then(res => {
          this.$emit('updateCart');
          Vue.bus.$emit('update-notifications');
        })
        .catch(err => {
          console.error(err); 
        })
      })
      .catch(function() {
        console.log('Clicked on cancel');
      });
    },
    deleteSelectedProduct(){
      let options = {
        okText: 'Hapus semua',
        cancelText: 'Batal',
        }
      this.$dialog
      .confirm('Hapus semua produk yang terpilih ?', options)
      .then(zzz => {
        this.doDeleteSelectedProduct();
      }
      )
      .catch(function() {
        console.log('Clicked on cancel');
      });
    },
    doDeleteSelectedProduct(){
      var data = []
      for(var i in this.selectedProduct){
        data.push(this.selectedProduct[i]);
      }
      axios.delete('/api/user/carts/'+data)
      .then(res => {
        this.$emit('updateCart');
        Vue.bus.$emit('update-notifications');
      })
      .catch(err => {
        console.error(err); 
      })
    },
    Slug(value){
      return helper.toSlug(value);
    }
  },
  watch: {
    cart: function(){
      for(var i in this.cart){
        for(var x in this.cart[i]){
          Vue.set( this.selectedCart, this.cart[i][x].id, {"id": this.cart[i][x].id, "product_id": this.cart[i][x].product_id, "quantity": this.cart[i][x].quantity})
        }
      }
    },
    selectedCart: {
      deep: true,
      handler: _.debounce(function (val) {
        for(var i in this.selectedCart){
          if(this.selectedCart[i].updated == true){
            this.selectedCartIndex = i
            axios.patch('/api/user/carts/'+this.selectedCartIndex,{updateQuantity : this.selectedCart[i].quantity})
            .then(res => {
            })
            .catch(err => {
              console.error(err); 
            })
          }
          }
      },1000)
    },
    allSelected: function(){
      this.selectedProduct = [];
      this.selected_store = [];
      this.$emit('selectedCart', []);
      var key = '';
      var key2 = '';
      if (this.allSelected == true) {
        for(key in this.cart){
          this.selected_store.push(this.cart[key][0].product.store_id)
        }
      }
    },
     selected_store: function(){
      this.selectedProduct = [];
      for(var i in this.selected_store){
        for(var p in this.cart[this.selected_store[i]]){
          this.selectedProduct.push(this.cart[this.selected_store[i]][p].id)
        }
      }
    },
    selectedProduct: function(){
      this.$emit('selectedCartTemp', this.selectedProduct);
      this.totalPrice = 0
      for(var i in this.cart){
        for(var p in this.cart[i]){
          for(var s in this.selectedProduct){
            if(this.selectedProduct[s] == this.cart[i][p].id){
              var subTotal = this.cart[i][p].product.price * this.cart[i][p].quantity
              this.totalPrice = this.totalPrice + subTotal
            }
          }
        }
      }
    },
    totalPrice: function(){
      this.$emit('totalPrice', this.totalPrice);
    }
  },
  filters: {
    formatRupiah(value){
      return helper.formatRupiah(value);
    }
  },
  computed: {
        
  }
}
</script>
