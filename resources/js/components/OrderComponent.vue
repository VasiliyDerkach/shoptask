
<template>
    <div>
        <div v-if='errors.length' class="alert alert-warning" role="alert">
            <span v-for='(error,idx) in errors' :key='idx'>
                {{error}}<br>
            </span>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Наименование</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                    <tr  v-for='(product,idx) in vproducts' :key='product.id'>
                        <td>{{idx + 1}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.price}}</td>
                        <td class="product-buttons">
                            <button @click="cartAction('removeFrom',product.id)" class="btn btn-danger">-</button>
                            {{ product.quantity }}
                            <button @click="cartAction('addTo',product.id)" class="btn btn-success">+</button>
                        </td>
                        <td>
                            {{ Number(product.quantity*product.price).toFixed(2) }}
                        </td>
                    </tr>
                    <tr v-if='!vproducts.length'>
                        <td class="text-center" colspan="5">
                            Корзина пока пуста, начните <a href="/">покупать!</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-end">Итого:</td>
                        <td>
                            <strong>
                            {{ Number(summ).toFixed(2) }}
                            </strong>
                        </td>
                    </tr>
            </tbody>
        </table>
        <div class="mb-3">

        <select v-model='selOrder' class="form-control mb-5" @click='getData' @change='getProductData'>
            <option :value=null selected disabled>--Выберите заказ--</option>
            <option v-for="(order,indx) in orders" :value="order.id" :key='order.id' >
                {{indx+1}}.{{order.created_at}}
            </option>
        </select>
        <br>
        <a>Выбрано {{selOrder}}</a><br>
        <button v-if="(selOrder)" class="btn-success mb-5" @click="addInOrder">
            Добавить заказ к корзине
        </button>
        <input v-if="(selOrder)" hidden name="selOrder" :v-model=selOrder>
        <a>Просмотр истории заказа</a>
        <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Наименование</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            
                <tr v-for="(product, idx) in products" :key="product.id">
                   
                    <td>{{idx + 1}}</td>
                    <td>{{product.name}}</td>
                    <td>{{product.price}}</td>
                    <td> {{ product.pivot.quantity }}</td>
                    <td>
                        {{ product.price * product.pivot.quantity }}
                    </td>
                </tr>
                <tr  v-if="!(products)">
                    <td class="text-center" colspan="5">
                        Заказ пуст 
                    </td>
                </tr>
            
                
        </tbody>
        </table>
        <input placeholder="Имя" class="form-control mb-2" name='name' v-model="user.name">
        <input placeholder="Почта" class="form-control mb-2" name='email' v-model="user.email">
         <input placeholder="Адрес" class="form-control mb-2" name='address' v-model="address">
         <template v-if='!user.name'>
            <input id='register_confirmation' name='register_confirmation' type="checkbox">
                <!-- не забудьте добавить оферту -->
            <label for="register_confirmation">Вы будете автоматически зарегистрированы</label>
            <br>
         </template>
        <button v-if='loading' class="btn btn-primery" type="button" disabled>
                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true">
                </span>
                Оформляем заказ...
        </button>        
        <button v-else @click='createOrder' type="submit" value="1" class="btn btn-success">
            Оформить заказ
        </button>
            

    </div>
    </div>
</template>

<script>
    export default {
        props: ['idUser','cart','prods','user','address'],
        mounted() {
            console.log('cart',this.cart)
            console.log('products',this.products)
             
            },
        computed: 
        {
            summ() {
                return this.vproducts.reduce((sum,product) =>
                    {
                        return sum += product.quantity*product.price
                    },0)

            }
        },   
        data() {
            return {
                selOrder: null,
                products:[],
                vproducts: this.prods,
                orders: [],
                errors:[],
                loading: false
                }
            },
        methods: {
            createOrder()
            {
                this.loading=true
                this.errors=[]
                const params= {
                    name: this.user.name,
                    email: this.user.email,
                    address: this.address
                }
              
                axios.post('/cart/createOrder',params)
                    .then ( () => {
                        document.location.href='/'
                    }
                    )
                    .catch(error =>
                    {
                        const errors=error.response.data.errors
                        for (let err in errors)
                        {
                            errors[err].forEach(e => {
                                this.errors.push(e)
                            });

                        }
                    })
                    .finally(() =>
                        {
                            this.loading=false
                        }

                    )

            },
            cartAction(type,id){
                const params={id}
                axios.post(`/cart/${type}Cart`,params)
                .then(response => {
                    const index = this.vproducts.findIndex((product) => {
                        
                        return product.id== id
                    })
                    console.log(this.vproducts)
                    console.log(index)
                    console.log('response.data=',response.data)
                    if (response.data>0)
                        {
                            this.vproducts[index].quantity = response.data
                        }
                        else
                        {
                            this.vproducts.splice(index,1)
                        }
                    
                    
                })
            },
            getData() {
                const params={ id : this.idUser }
                
                axios.get('/api/orders', { params } )
                .then( response =>{
                        //console.log(response)
                        this.orders=response.data
                        
                    }
                )
                
            },
            getProductData(){
                const params={ orderid : this.selOrder }
                    axios.get('api/orderproducts', { params } )
                    
                .then( response =>{
                        console.log('orderid=',params)
                        console.log(response)
                        this.products=response.data
                    }
                )
            },
            addInOrder() {
                
                 const params={ orderid: this.selOrder }
                
                 console.log('orderidAdd0=',params)

                    axios.get('/cart/addInOrder', { params } )
                   .then( response =>{
                        
                        console.log('respadd=',response.data)
                        console.log('vpro=',this.vproducts)
                        for (let cr in response.data)
                        {
                            console.log(cr);
                            console.log(response.data[cr]);
                            this.vproducts.push({ 'id': cr,'quantity': response.data[cr]['quantity'],
                            'name': response.data[cr]['name'],
                            'price': response.data[cr]['price']});
                            console.log(cr);
                        }
                     }
                    )            
                
        
            }
    }
    }
</script>
<style scoped>

</style>
