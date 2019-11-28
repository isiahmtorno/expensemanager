<template>
    <main class="main-content" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%);">
        <div class="main-content-container container-fluid px-4 my-auto h-100">
            <div class="row no-gutters h-100 justify-content-center">
                <div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">
                    <div class="card">
                        <div class="card-body">
                            <img class="auth-form__logo d-table mx-auto mb-3" src="images/logo.svg" alt="Shards Dashboards - Register Template">
                            <h5 class="auth-form__title text-center mb-4">Access Your Account</h5>
                            <form @submit="login">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="text" class="form-control" name="email" v-model="email" aria-describedby="emailHelp" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name="password" v-model="password" placeholder="Password">
                                </div>
                                <button type="submit" class="btn btn-pill btn-accent d-table mx-auto">Login here</button>
                            </form>
                        </div>
                    </div>
                    <div class="ajax-response mt-3">
                        <div class="ajax-message"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        data() {
            return {
                email : '',
                password : ''
            }
        },
        mounted() {
            
        },
        methods: {
            login(e) {
                e.preventDefault();
                let data = {
                    email: this.email,
                    password: this.password
                }
                axios.post('/login', data).then(response => {

                    if(response.data.status){
                        this.$emit('user', response.data.user);
                        this.$router.push('/dashboard');
                    }else{

                        $('.ajax-response').show();
                        $('.ajax-message').html(response.data.message);

                        setTimeout(function(){
                            $('.ajax-response').fadeOut();
                        },5000);
                    }

                });
            }
        }
    }
</script>
