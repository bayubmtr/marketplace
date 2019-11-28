<template>
<div>
<b-modal id="saleDetail" size="xl" v-if="selectedOrder" title="Detail Pesanan" scrollable no-close-on-backdrop hide-footer>
        <b-row v-if="selectedOrder.id">
            <b-col cols="6">
                <b-row>
                    <b-col cols="2" class="pr-0">
                        <div class="text-muted">
                            No Pesanan
                        </div>
                    </b-col>
                    <b-col cols="10">
                        <div class="text-muted">
                            : <strong>{{selectedOrder.id}}</strong>
                        </div>
                    </b-col>
                    <b-col cols="2">
                        <div class="text-muted">
                            Pembeli
                        </div>
                    </b-col>
                    <b-col cols="10">
                        <div class="text-muted">
                            : <strong>{{selectedOrder.order.user.first_name+' '+selectedOrder.order.user.last_name}}</strong>
                        </div>
                    </b-col>
                </b-row>
            </b-col>
            <b-col cols="6">
                <div class="float-right">
                    <a :href="'/invoice/'+selectedOrder.id+'/print'" target="_blank">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Cetak Bukti Pembayaran</button>
                    </a>
                </div>
            </b-col>
            <b-col cols="6">
                <b-card body-class="p-1 mt-2" class="mt-3" header="Pesanan">
                    <b-row>
                        <b-col cols="6" class="text-center">
                            Produk
                        </b-col>
                        <b-col cols="2" class="p-0 text-center">
                            Harga
                        </b-col>
                        <b-col cols="2" class="text-center">
                            Diskon
                        </b-col>
                        <b-col cols="2" class="p-0 text-center">
                            Sub Total
                        </b-col>
                        <b-col cols="12">
                            <hr>
                        </b-col>
                        <b-col cols="12">
                            <template class="pt-2"  v-for="(item, index) in selectedOrder.order_item">
                                <b-row :key="index">
                                    <b-col cols="6" class="text-truncate">
                                    <div style="height: 63px; line-height: 63px;">
                                        <span><img class="img-thumb" :src="item.product.photo.small" alt=""></span>
                                        <router-link class="product-click" :to="'/'+selectedOrder.store.store_url+'/'+item.product.id+'/'+item.product.name | Slug">
                                        <strong class="pl-2 text-muted">{{item.product.name}}</strong>
                                        </router-link>
                                    </div>
                                    <div class="pl-5">
                                        <strong>Catatan : </strong>
                                        <span class="pl-2 text-muted" style="white-space: pre;"  data-toggle="tooltip" data-placement="bottom" :title="item.note">{{item.note}}</span>
                                    </div>
                                </b-col>
                                <b-col cols="2" class="text-right p-0 pr-2" style="height: 63px; line-height: 63px;">
                                    <span class="align-middle">{{item.price | formatRupiah}}</span>
                                </b-col>
                                <b-col cols="2" class="text-center" style="height: 63px; line-height: 63px;">
                                    <span class="align-middle" v-if="item.discount != 0">{{item.discount}}</span>
                                    <span class="align-middle" v-else>--</span>
                                </b-col>
                                <b-col cols="2" class="text-right p-0 pr-4" style="height: 63px; line-height: 63px;">
                                    <span class="align-middle">{{selectedOrder.sub_total_price | formatRupiah}}</span>
                                </b-col>
                                </b-row>
                        </template>
                        </b-col>
                        <b-col cols="12">
                            <hr>
                        </b-col>
                        <b-col cols="9">
                            <span class="float-right">Total Pembelian :</span>
                        </b-col>
                        <b-col cols="3" class="text-right p-0 pr-4">
                            {{selectedOrder.sub_total_price | formatRupiah}}
                        </b-col>
                        <b-col cols="9">
                            <span class="float-right">Biaya Kirim :</span>
                        </b-col>
                        <b-col cols="3" class="text-right p-0 pr-4">
                            {{selectedOrder.order_shipment_detail.shipment_cost | formatRupiah}}
                        </b-col>
                        <b-col cols="9">
                            <span class="float-right">Total Belanja :</span>
                        </b-col>
                        <b-col cols="3" class="text-right p-0 pr-4">
                            {{selectedOrder.order_shipment_detail.shipment_cost+selectedOrder.sub_total_price | formatRupiah}}
                        </b-col>
                        <b-col cols="12">
                            <hr>
                        </b-col>
                        <div class="pl-5">
                            <div>
                                <span>Jasa Pengiriman : </span>
                            </div>
                            <div>
                                <strong>{{selectedOrder.order_shipment_detail.logistic.name+' - '+selectedOrder.order_shipment_detail.logistic_service}}</strong>
                            </div>
                            <br>
                        </div>
                    </b-row>
                </b-card>
            </b-col>
            <b-col cols="6">
                <b-card class="mt-3" header="Pengiriman">
                    <b-row>
                        <b-col cols="4">
                            Nama Penerima
                        </b-col>
                        <b-col cols="8">
                            : {{selectedOrder.order.order_shipment.recipient}}
                        </b-col>
                        <b-col cols="4">
                            Kelurahan
                        </b-col>
                        <b-col cols="8">
                            : {{selectedOrder.order.order_shipment.address.village}}
                        </b-col>
                        <b-col cols="4">
                            Kecamatan
                        </b-col>
                        <b-col cols="8">
                            : {{selectedOrder.order.order_shipment.address.district}}
                        </b-col>
                        <b-col cols="4">
                            Kab/Kota
                        </b-col>
                        <b-col cols="8">
                            : {{selectedOrder.order.order_shipment.address.city}}
                        </b-col>
                        <b-col cols="4">
                            Provinsi
                        </b-col>
                        <b-col cols="8">
                            : {{selectedOrder.order.order_shipment.address.province}}
                        </b-col>
                        <b-col cols="4">
                            Kode Pos
                        </b-col>
                        <b-col cols="8">
                            : {{selectedOrder.order.order_shipment.address.zip_code}}
                        </b-col>
                        <b-col cols="4">
                            Detail Alamat
                        </b-col>
                        <b-col cols="8">
                            <div>
                                <span style="white-space: pre;"> : {{selectedOrder.order.order_shipment.address_detail}}</span>
                            </div>
                        </b-col>
                    </b-row>
                </b-card>
                <div class="float-right">
                    <button v-on:click="openChat" type="button" class="btn btn-outline-primary">Chat Pembeli</button>
                    <button type="button" class="btn btn-outline-primary" v-if="selectedOrder.order_status_id == 2">Terima Pesanan</button>
                    <button type="button" class="btn btn-outline-primary" v-if="selectedOrder.order_status_id == 3">Kirim Sekarang</button>
                    <button type="button" class="btn btn-outline-danger" v-if="selectedOrder.order_status_id == 2">Tolak Pesanan</button>
                </div>
            </b-col>
        </b-row>
    </b-modal>
    <newChat v-if="starNewChat.active" :starNewChat="starNewChat" v-on:updateStarNewChat="updateStarNewChat"></newChat>
    </div>
</template>
<script>
import newChat from '../../message/newMessage'
import helper from '../../../services/helper'
export default {
    props: [
    'selectedOrder'
  ],
  name: 'saleDetail',
  components: {
      newChat
  },
  data () {
    return {
        showModal : '',
        starNewChat : {
            active : false,
            receiver_type : 'user',
            receiver_id : '',
            receiver_name : ''
        }
        
  }
  },
  mounted() {
  },
  methods: {
      showDetail(order_id, modal_id){
        axios.get('/api/user/seller/sales/'+order_id)
        .then(res => {
            this.selectedOrder = res.data;
            this.showModal = modal_id
        })
        .catch(err => {
            console.error(err); 
        })
    },
    openChat(){
        this.starNewChat.receiver_id = this.selectedOrder.order.user_id
        this.starNewChat.receiver_name = this.selectedOrder.order.user.first_name+' '+this.selectedOrder.order.user.last_name
        this.starNewChat.active = true
        this.$bvModal.hide('saleDetail');
    },
    updateStarNewChat(){
      this.starNewChat.active = false;
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
      selectedOrder(){
         this.$bvModal.show('saleDetail');
    }
  },
}
</script>