<template>
    <div class="wrapper">
        <div class="animated fadeIn p-2">
            <div>
                <b-row class="pl-4">
                    <span class="text-besar text-tebal pb-3">Informasi</span>
                    <b-col cols="12">
                    <b-row>
                        <b-col cols="2" class="text-muted pb-2">
                            <span>Kategori</span>
                        </b-col>
                        <b-col cols="10" v-if="product.category">
                            <span class="text-sedang">
                                <span v-if="product.category.parent.parent_id != null"> {{product.category.parent.parent.name}} <i class="fa fa-angle-double-right"></i></span>
                                <span> {{product.category.parent.name}} <i class="fa fa-angle-double-right" v-if="product.category.parent"></i></span>
                                <span> {{product.category.name}} </span>
                            </span>
                        </b-col>
                        <b-col cols="2" class="text-muted pb-2 pr-0">
                            <span>Terakhir diperbarui</span>
                        </b-col>
                        <b-col cols="10">
                            <span class="text-sedang">{{product.updated_at | moment("Do MMMM YYYY")}}</span>
                        </b-col>
                        <b-col cols="2" class="text-muted pb-2">
                            <span>Kondisi</span>
                        </b-col>
                        <b-col cols="10">
                            <span class="text-sedang">{{product.condition}}</span>
                        </b-col>

                        <b-col cols="2" class="text-muted pb-2">
                            <span>Stok</span>
                        </b-col>
                        <b-col cols="10">
                            <span class="text-sedang">{{product.stock}}</span>
                        </b-col>
                        <b-col cols="2" class="text-muted pb-2">
                            <span>Berat</span>
                        </b-col>
                        <b-col cols="10">
                            <span class="text-sedang">{{product.weight}} Kg</span>
                        </b-col>
                        <b-col cols="2" class="text-muted pb-2">
                            <span>Dikirim Dari</span>
                        </b-col>
                        <b-col cols="10">
                            <span class="text-sedang"> {{product.location}}
                            </span>
                        </b-col>
                        <template v-if="product.specification">
                            <template v-for="(item, index) in product.specification">
                                <b-col cols="2" class="text-muted pb-2" :key="index">
                                    <span>{{item.name}}</span>
                                </b-col>
                                <b-col cols="10" :key="item.id">
                                    <span class="text-sedang"> {{item.value}}
                                    </span>
                                </b-col>
                            </template>
                        </template>
                    </b-row>
                    <hr>
                    </b-col>
                    <span class="text-besar text-tebal pt-4 pb-3">Deskripsi</span>
                </b-row>
            </div>
            <div >
                <div class="pl-4 deskripsi">{{product.description}}</div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: [
    'product'
  ],
}
</script>
