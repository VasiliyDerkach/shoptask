
<template>
<div>
    <!--
    <div v-if='errors.length' class="alert alert-warning" role="alert">
            <span v-for='(error,idx) in errors' :key='idx'>
                {{error}}<br>
            </span>
    </div>
   
    <div v-if='addressExist' class="alert alert-success" role="alert">
        Такой адресс уже существует!
    </div>
    <div v-if='profileSaved' class="alert alert-success" role="alert">
        Профиль успешно сохранен!
    </div>
    -->
    <div class="mb-3">
            <label class="form-label">Изображение профиля</label>
            <img class="user-picture mb-2" :src="`/storage/${user.picture}`">
            
            <input type="file" id="file" ref="file" @change="inputPicture()">
            
    </div>
    
    <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Почта</label>
            <input type="email" v-model="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
            
    </div>
    <div class="mb-3">
            <label class="form-label">Имя</label>
            <input name="name" v-model="username" class="form-control">
    </div>
    
    <div class="mb-3">
            <label class="form-label">Текущий пароль</label>
            <input type="password" autocomplete="off" v-model="current_password" class="form-control" >
        </div>
        <div class="mb-3">
            <label class="form-label">Новый пароль</label>
            <input type="password" v-model="password" class="form-control" >
        </div>
        <div class="mb-3">
            <label class="form-label">Повторите новый пароль</label>
            <input type="password" v-model="confpassword" class="form-control" >
        </div>
        
        <div class="mb-3">
            <label class="form-label">Список адресов</label>
            <table class="table table-bordered">
        
        
            <thead>
                <tr>
                    
                    <th>Наименование</th>
                    
                </tr>
            </thead>
            <tbody>
                
                    <tr v-for='adress in addressess' :key='adress.id'>
                        
                        
                        <td>
                            <input :id="adress.id" name="adrs" :checked ="adress.main == 1"  type="radio" @change="changeMainAdress(adress.id)">
                           
                            <label name="adrs" for="adress.id">{{adress.id}}.{{adress.address}}({{adress.main==1}})</label>
                            
                        </td>
                        
                    </tr>
                    <tr  v-if="!addressess">
                        <td class="text-center" colspan="2">
                            Адресов пока нет 
                        </td>
                    </tr>
            </tbody>
            </table>
            <a>{{mainAdressId}}</a>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Новый адрес</label>
            <input name="new_address" class="form-control" v-model="newAdress">
           
                <input v-model="maincheck" class="form-check-input" type="checkbox" value="0" id="flexCheckDefault">
                <label class="form-check-label" for="flexCheckDefault">
                    Основной адрес
                </label>
            
        </div>
        <button type="submit" class="btn btn-primary" @click="SaveProfile">Сохранить</button>


</div>
</template>
<script>

    export default {
        props: ['idUser','user','useraddress'],
       
        data() {
            return {
                email: this.user.email,
                addressess: this.useraddress,
                username: this.user.name,
                file: '',
                errors: [],
                maincheck: false,
                newAdress: null,
                mainAdressId: null,
                picture:'',
                addressExist: false,
                profileSaved: false,
                password: null,
                currpasswor: null,
                confpassword: null
                
            }
        },
            methods: {
                inputPicture() {
                    let formData= new FormData()
                    formData.append('file',this.file)
                    axios.post('/single-file',
                        formData,
                        {
                            headers:{
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    )
                    .then(function() {
                        console.log('Изображение сохранено');
                    }
                    )
                    .catch(function(){
                        console.log('Ошибка сохранения изображения');
                    });
                },
                
                changeMainAdress(idd)
                {
                    this.mainAdressId=idd
                    const params= {
                         userId: this.user.id , 
                         mainaddress: idd 
                    }
                    console.log(params)
                   
                    axios.get('/profilejs/saveMainAdrs', { params } )
                     .then(response => {
                         console.log('response.data=',response.data)
                     }
                     )
                },

                SaveProfile()
                {
                    this.errors=[]
                    this.file=this.$refs.file.files[0]
                    const params=
                    {
                        picture: this.file,
                        maincheck: this.maincheck,
                        newAdress: this.newAdress,
                        password: this.password,
                        currpassword: this.currpassword,
                        confpassword: this.confpassword,
                        name: this.name,
                        email:this.email,
                        mainCheck: this.maincheck,    
                        userId: this.userId,
                        user: this.user

                    }

                    axios.get('/profilejs/saveprofilejs', { params } )
                    .then( response =>{
                        
                        console.log('resp=',response)
                        this.addressExist=response.data.addressExist
                        this.profileSaved=response.data.profileSaved
                        
                     })
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
                         
                }

            }
        
//        ,computed(): {}
    }
</script>
<style scoped>

</style>
