<template>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">My Profile</span>
                <h3 class="page-title">Change Password</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <form @submit="save">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" v-model="current_password" name="current_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" v-model="new_password" name="new_password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" v-model="confirm_password" name="confirm_password" class="form-control">
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>

                    <div class="ajax-response">
                        <div class="ajax-message"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data(){
            return{
                current_password: '',
                new_password: '',
                confirm_password: ''
            }
        },
        mounted() {
        },
        methods: {
            save(e) {
                e.preventDefault();
                let data = {
                    current_password: this.current_password,
                    new_password: this.new_password,
                    confirm_password: this.confirm_password
                };
                axios.post('usersapi/save-profile', data).then(response => {

                    if(response.data.status){

                        $('.ajax-response').show();
                        $('.ajax-message').html(response.data.message);

                        this.current_password = '';
                        this.new_password = '';
                        this.confirm_password = '';

                        setTimeout(function(){
                            $('.ajax-response').fadeOut();
                        },2000);

                    }else{
                        let html = '<div class="alert alert-danger mb-4">';
                        html += '<ul class="list-unstyled mb-0">';
                        response.data.message.forEach((value, key) => {
                            html += '<li>'+ value +'</li>';
                        });
                        html += '</ul>\
                            </div>';

                        $('.ajax-response').show();
                        $('.ajax-message').html(html);

                        setTimeout(function(){
                            $('.ajax-response').fadeOut();
                        },5000);
                    }

                });
            }
        }
    }
</script>
