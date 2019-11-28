
export default {
    logout(){
        return axios.post('/api/user/logout').then(response =>  {
            localStorage.removeItem('auth_token');
            axios.defaults.headers.common['Authorization'] = null;
            toastr['success']('Anda Berhasil Keluar');
        }).catch(error => {
            console.log(error);
        });
    },

    authUser(){
        return axios.get('/api/user/auth/user').then(response =>  {
            return response.data;
        }).catch(error => {
            return error.response.data;
        });
    },

    check(){
        return axios.post('/api/user/check').then(response =>  {
            return !!response.data.authenticated;
        }).catch(error =>{
            return response.data.authenticated;
        });
    },

    checkSeller(){
        return axios.get('/api/user/seller/stores').then(response =>  {
            if(response.data.is_seller == false){
                return false;
            }else{
                return true
            }
        }).catch(error =>{
            return response.data.is_seller;
        });
    },

    getFilterURL(data){
        let url = '';
        $.each(data, function(key,value) {
            url += (value) ? '&'+key+'='+encodeURI(value) : '';
        });
        return url;
    },

    formAssign(form, data){
        for (let key of Object.keys(form)) {
            if(key !== "originalData" && key !== "errors" && key !== "autoReset"){
                form[key] = data[key];
            }
        }
        return form;
    },

    taskColor(value){
        let classes = ['progress-bar','progress-bar-striped'];
        if(value < 20)
            classes.push('bg-danger');
        else if(value < 50)
            classes.push('bg-warning');
        else if(value < 80)
            classes.push('bg-info');
        else
            classes.push('bg-success');
        return classes;
    },

    formatDate(date){
        if(!date)
            return;
        
        return Vue.moment(date).format("Do MMM YY");    
    },
    formatDateTime(date){
        if(!date)
            return;

        return Vue.moment(date).format('MMMM Do YYYY h:mm a');
    },

    ucword(value){
        if(!value)
            return;

        return value.toLowerCase().replace(/\b[a-z]/g, function(value) {
            return value.toUpperCase();
        });
    },
    gender(value){
        if(!value)
            return;

        let ubah = ''
        if(value == 'M')
            ubah = 'L';
        else if(value == 'F')
            ubah = 'P';
        return ubah;
    },
    formatRupiah(value){
        if(!value)
            return 'Rp 0';
        let val = (value/1)
        return 'Rp '+ val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    },
    toSlug(value){
        if(!value)
            return;
        let filter = value.replace(/[^a-zA-Z 0-9]/g, "");
        let val = filter.split(' ').join('-');
        val = val.replace(/\//g, '-');
        return val
    },
    toUrl(value){
        if(!value)
            return;
        let val = value.split('%2F').join('/');
        return val
    },
    truncateString(value,length){
        if(!value && !length)
            return;
        if(value.length < 20)
            return value;
        let val = value.substring(0,length)+'...';
        return val;
    },

    //seller
    acceptOrder(id){
        axios.patch('/api/user/seller/sales/'+id,{type : 'accept'})
        .then(response => {
            toastr.success('Berhasil Menerima Pesanan')
            return response.data
        })
        .catch(err => {
            toastr.error('Gagal Memproses permintaan !')
            return err.response;
        })
    },
    
    
}
