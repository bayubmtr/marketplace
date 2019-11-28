<template>
  <div class="animated fadeIn">
    <b-card>
      <div slot="header">
          Ubah Produk
      </div>
      <div>
        <b-form-group label-cols="4" label-cols-lg="2" label="Nama" label-for="input-sm">
            <b-form-input v-model="productInfo.name" id="product_name" placeholder="masukan nama barang"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="4" label-cols-lg="2" label="Harga Satuan" label-for="input-default">
            <b-form-input v-model="productInfo.price" placeholder="kelipatan 100" id="product_price" style="width : 200px"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="4" label-cols-lg="2" label="Berat" label-for="input-default">
            <b-form-input v-model="productInfo.weight" id="product_weight" placeholder="dalam kilo gram" style="width : 200px"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="4" label-cols-lg="2" label="Stok" label-for="input-default">
            <b-form-input v-model="productInfo.stock" id="product_stock" placeholder="Jumlah stok barang" style="width : 200px"></b-form-input>
        </b-form-group>
        <b-form-group label-cols="4" label-cols-lg="2" label="Kondisi" label-for="input-lg">
          <b-form-radio-group id="product_condition" v-model="productInfo.condition" name="radio-sub-component">
            <b-form-radio value="New">Baru</b-form-radio>
            <b-form-radio value="Used">Bekas</b-form-radio>
          </b-form-radio-group>
        </b-form-group>
        <b-form-group label-cols="4" label-cols-lg="2" label="Deskripsi" label-for="input-default">
            <b-form-textarea
              id="textarea"
              v-model="productInfo.description"
              placeholder="Tulis keterangan barang ..."
              rows="4"
              max-rows="6"
            ></b-form-textarea>
        </b-form-group>
        <b-form-group label-cols="4" label-cols-lg="2" label="Spesifikasi" label-for="input-default">
          <b-row v-for="(item, index) in specificationCount" :key="index" class="mb-3">
            <b-col cols="4">
              <b-form-input v-model="productInfo.specification[index].name" placeholder="nama spesifikasi"></b-form-input>
            </b-col>
            <b-col cols="7">
              <b-form-input v-model="productInfo.specification[index].value" placeholder="nilai spesifikasi"></b-form-input>
            </b-col>
          </b-row>
          <b-button v-if="specificationCount < 5" v-on:click="setSpecificationCount()" variant="outline-primary">Tambah Spesifikasi</b-button>
          <b-button v-if="specificationCount > 0" v-on:click="removeSpecification()" variant="outline-danger">Hapus Spesifikasi</b-button>
        </b-form-group>
        <div class="text-center">
            <b-button variant="primary" v-on:click="storeProduct()">Simpan Barang</b-button>
        </div>
      </div>
    </b-card>
  </div>
</template>

<script>

export default {
  name: 'ProductNew',
  components: {
  },
  data: function () {
    return {
      
      main_categories : {},
      sub_categories :[],
      productPhotos : [],
      productInfo : {
        id : this.$route.params.product_id,
        name : '',
        price : '',
        condition : '',
        description : '',
        weight : '',
        stock : '',
        type : 'update',
        specification : [
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          },
          {
            name : '',
            value : ''
          }
        ]
      },
      specificationCount : 0
    }
  },
  mounted() {
    this.getProduct(this.productInfo.id);
  },
  methods: {
    storeProduct(){
      this.productInfo.type = 'update'
      axios.patch('/api/user/seller/products/'+this.productInfo.id,this.productInfo)
      .then(res => {
        toastr.success('Berhasil Memperbarui Produk !')
        this.getProduct(this.productInfo.id);
      })
      .catch(err => {
        toastr.error('Gagal Memperbarui Produk !')
      })
    },
    getProduct(id){
      axios.get('/api/user/seller/products/'+id)
      .then(res => {
          this.productInfo = res.data
         this.specificationCount = res.data.specification.length
      })
      .catch(err => {
        console.error(err); 
      })
    },
    removeSpecification(){
      this.specificationCount = this.specificationCount - 1;
      this.productInfo.specification[this.specificationCount].name = null;
      this.productInfo.specification[this.specificationCount].value = null;
    },
    setSpecificationCount(){
      if(this.specificationCount < 5){
        this.specificationCount = this.specificationCount + 1
      }
    }
  }
}
</script>