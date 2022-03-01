use App\Models\Category;
<template>
    <div>
        <select v-model='selCategory' class="form-control mb 7" @click='getData'>
            <option :value=null selected disabled>--Выберите категорию--</option>
            <option v-for="(categ,indx) in categories" :value="categ.id" :key='categ.id'>
                {{indx}}.{{categ.name}}
            </option>
        </select>
        <br>
        <a>{{selCategory}}</a>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')},
        data() {
            return {
                selCategory: null,
                categoryId: null,
                categories: []
                }
            },
        methods: {
            getData() {
                axios.get('/api/categories')
                .then( response =>{
                        console.log(response.data)
                        this.categories=response.data
                    }
                )
                
            }
        
        }
        
    }
</script>
<style scoped>

</style>
