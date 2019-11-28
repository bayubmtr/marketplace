<template>
  <div class="wrapper">
    <div class="animated fadeIn">
      <template v-if="invoice">
        <b-card>
        <div slot="header">
          <span class="lead">Detail Order</span>
        </div>
          <b-col cols="12">
            <b-card>
            <b-row>
              <b-col cols="6">
                <div>
                <div>
                  <span class="text-muted text-kecil">NO. Tagihan</span>
                </div>
                <div>
                  <span>{{invoice.id}} </span> <template v-if="invoice">
                    <a v-if="invoice.payment_status_id == 3" :href="'/invoice/'+invoice.id+'/print'" target="_blank"><span class="text-primary mouse-pointer"> Cetak Tagihan</span></a>
                  </template>
                </div>
                <div class="dropdown-divider"></div>
              </div>
              <div>
                <div>
                  <span class="text-muted text-kecil">STATUS TAGIHAN</span>
                </div>
                <div v-if="invoice.payment_status">
                  <span>{{invoice.payment_status.name}}</span>
                </div>
                <div v-else>
                  <span>Belum dibayar</span>
                </div>
                <div class="dropdown-divider"></div>
              </div>
              </b-col>
              <b-col cols="6">
              <div>
                <div>
                  <span class="text-muted text-kecil">METODE PEMBAYARAN</span>
                </div>
                <div v-if="invoice.payment_type">
                  <span>{{invoice.payment_type.name}}</span>
                </div>
                <div v-else>
                  <span>Belum dipilih</span>
                </div>
                <div class="dropdown-divider"></div>
              </div>
              <div>
                <div>
                  <span class="text-muted text-kecil">TOTAL PEMBAYARAN</span>
                </div>
                <div>
                  <span>{{invoice.total_payment | formatRupiah}}</span>
                </div>
                <div class="dropdown-divider"></div>
              </div>
              </b-col>
            </b-row>
            </b-card>
          </b-col>
          <b-col cols="12" v-if="invoice.order">
            <template v-for="data in invoice.order.order_detail">
              <b-card :key="data.id">
                <b-row>
                  <b-col cols="6">
                    <b-card-header class="border border-bottom-0">
                      <b-row>
                        <b-col cols="5">
                          <small class="text-muted">NO TRANSAKSI</small>
                          <div>
                            <span>{{data.id}}</span>
                          </div>
                        </b-col>
                        <b-col cols="7 text-truncate">
                          <small class="text-muted">STATUS</small>
                          <div>
                            <span>{{data.order_status.name}}</span>
                          </div>
                        </b-col>
                      </b-row>
                    </b-card-header>
                    <b-card-header class="border border-bottom-0">
                      <b-row>
                        <b-col cols="5  text-truncate">
                          <small class="text-muted">PENJUAL</small>
                          <div>
                            <span>{{data.store.name}}</span>
                          </div>
                        </b-col>
                        <b-col cols="7">
                          <small class="text-muted">KIRIM PESAN</small>
                          <div>
                            <span v-on:click="sendNewChat" class="fa fa-comments-o mouse-pointer"></span>
                          </div>
                        </b-col>
                      </b-row>
                    </b-card-header>
                    <b-card-header class="border border-bottom-0">
                      <b-row>
                        <b-col cols="5  text-truncate">
                          <small class="text-muted">BIAYA PENGIRIMAN </small>
                        </b-col>
                        <b-col cols="7">
                          <span>: {{data.order_shipment_detail.shipment_cost | formatRupiah}}</span>
                        </b-col>
                      </b-row>
                    </b-card-header>
                    <b-card-header class="border">
                      <div slot="header">
                        <template v-for="item in data.order_item">
                          <b-row :key="item.id">
                            <b-col cols="2">
                              <div class="pt-2">
                                <span><img class="img-thumb" :src="item.product.photo.small" alt=""></span>
                            </div>
                            </b-col>
                            <b-col cols="10">
                                <div>
                                    <span style="width:230px" class="d-inline-block text-truncate">{{item.product.name}}</span>
                                    <span class="float-right">{{item.price | formatRupiah}}</span>
                                </div>
                                <div>
                                  <small class="tetx-muted">Jumlah : </small> <small>{{item.quantity}}</small>
                                </div>
                                <div>
                                  <small class="tetx-muted">Berat :  </small> <small>{{item.weight}} kg</small>
                                </div>
                            </b-col>
                          </b-row>
                            <b-card-header class="border border-1">
                              <small class="text-muted">CATATAN UNTUK PENJUAL</small>
                                <div>
                                  <span>{{item.note}}</span>
                                </div>
                            </b-card-header>
                        </template>
                      </div>
                    </b-card-header>
                  </b-col>
                  <b-col>
                    <b-card-header class="border" v-if="invoice.order">
                        <small class="text-muted">ALAMAT PENGIRIMAN</small>
                        <div>
                          <strong>{{invoice.order.order_shipment.recipient}}</strong><br>
                          <span style="white-space: pre;">{{invoice.order.order_shipment.address_detail}}</span>
                          <div>
                            Kel. {{invoice.order.order_shipment.address.village}} Kec {{invoice.order.order_shipment.address.district}}
                          </div> 
                          <div>
                            Kab/Kot. {{invoice.order.order_shipment.address.city}} Prov. {{invoice.order.order_shipment.address.province}} ({{invoice.order.order_shipment.address.zip_code}})
                          </div> 
                        </div>
                      </b-card-header>
                      <b-card-header class="border border-top-0">
                        <b-row>
                        <b-col cols="6 text-truncate">
                          <small class="text-muted">JASA PENGIRIMAN</small>
                          <div>
                            <span>{{data.order_shipment_detail.logistic.name}}</span>
                          </div>
                        </b-col>
                        <b-col cols="6 text-truncate">
                          <small class="text-muted">JENIS PELAYANAN</small>
                          <div>
                            <span>{{data.order_shipment_detail.logistic_service}}</span>
                          </div>
                        </b-col>
                      </b-row>
                      </b-card-header>
                      <b-card-header class="border border-top-0">
                      <small class="text-muted">NO. RESI</small>
                        <div>
                          <strong class="text-primary mouse-pointer" v-on:click="cekResi(data.order_shipment_detail.tracking_number)" v-if="data.order_shipment_detail.tracking_number">
                          {{data.order_shipment_detail.tracking_number}}
                          </strong>
                        <span v-else>--</span>
                        </div>
                      </b-card-header>
                      <b-card-header class="border border-top-0">
                        <b-row>
                        <b-col cols="6 text-truncate">
                          <small class="text-muted">BARANG DIKIRIM</small>
                          <div>
                            <span v-if="data.order_shipment_detail.sent_at">
                              {{data.order_shipment_detail.sent_at}}
                            </span>
                            <span v-else>--</span>
                          </div>
                        </b-col>
                        <b-col cols="6 text-truncate">
                          <small class="text-muted">BARANG SAMPAI</small>
                          <div>
                            <span v-if="data.order_shipment_detail.delivery_at">
                              {{data.order_shipment_detail.delivery_at}}
                            </span>
                            <span v-else>--</span>
                          </div>
                        </b-col>
                      </b-row>
                      </b-card-header>
                      <b-card-header class="border border-top-0">
                      <small class="text-muted">KETERANGAN</small>
                        <div>
                          <span>--</span>
                        </div>
                      </b-card-header>
                      <DIV class="mt-3 float-right">
                        <button type="button" v-if="data.order_status_id == 6 || data.order_status_id == 12" class="btn btn-outline-primary">Beli lagi</button>
                        <button v-on:click="acceptOrder()" type="button" class="btn btn-outline-primary" v-if="data.order_status_id == 4">Terima Pesanan</button>
                        <button type="button" class="btn btn-outline-primary" v-if="data.order_status_id == 6">Tulis ulasan</button>
                        <button type="button" class="btn btn-outline-primary" v-if="data.order_status_id == 1">Batalkan</button>
                      </DIV>
                  </b-col>
                </b-row>
              </b-card>
            </template>
          </b-col>
        </b-card>
      </template>
      <template v-else>
        <b-card-header class="border border-top-0">
          <strong>Terjadi masalah{{invoice}}</strong>
        </b-card-header>
      </template>}
    </div>
    <newChat v-if="starNewChat.active" :starNewChat="starNewChat" v-on:updateStarNewChat="updateStarNewChat"></newChat>
  </div>
</template>
<script>
import newChat from '../message/newMessage'
import helper from '../../services/helper'
export default {
  name: 'Payment',
  components: {
    newChat
  },
  data () {
    return {
        invoice: {},
        orderId : this.$route.params.orderId,
        snapToken : '',
        starNewChat : {
        active : false,
        receiver_type : 'store',
        receiver_id : '',
        receiver_name : ''
      }
    }
  },
  mounted() {
      this.getItem();
  },
  methods: {
    getItem(){
      axios.get('/api/user/purchases/'+this.orderId)
      .then(res => {
          this.invoice = res.data
      })
      .catch(err => {
          console.error(err); 
      })
    },
    html_entity_decode(message) {
  return message.replace(/[<>'"]/g, function(m) {
    return '&' + {
      '\'': 'apos',
      '"': 'quot',
      '&': 'amp',
      '<': 'lt',
      '>': 'gt',
    }[m] + ';';
  });
  },
  acceptOrder(){
    axios.patch('/api/user/purchases/'+this.invoice.order_id,{req_done : true})
    .then(res => {
      toastr.success('Transaksi Telah Selesai')
      this.getItem();
    })
    .catch(err => {
      console.error(err); 
    })
  },
  sendNewChat(id){
      this.starNewChat.active = true;
      this.starNewChat.receiver_id = this.invoice.order.order_detail[0].store_id;
      this.starNewChat.receiver_name = this.invoice.order.order_detail[0].store.name;
    },
  updateStarNewChat(){
      this.starNewChat.active = false;
    },
  cekResi: function (resi) {   
          window.open("https://cekresi.com/?v=wdg&noresi="+resi, "_blank");    
      }
  },
  computed: {
    time: function(){
      return this.date.format('mm:ss');
    }
  },
  filters: {
    formatDate(date) {
        return helper.formatDate(date);
    },
    formatRupiah(value){
      return helper.formatRupiah(value);
    },
  }
}
</script>