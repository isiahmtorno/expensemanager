<template>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">User Management</span>
                <h3 class="page-title">List of Users</h3>
            </div>
        </div>

        <button class="btn btn-primary mb-3" @click="open">Add User <i class="fas fa-plus ml-2"></i></button>
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th width="25%">Name</th>
                    <th width="30%">Email</th>
                    <th width="25%">Role</th>
                    <th width="20%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users">
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role.name }}</td>
                    <td v-if="user.role.name !== 'admin'">
                        <a href="javascript:void(0);" class="btn btn-secondary" @click="search(user)"><i class="fas fa-pencil-alt"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger" @click="delete1(user)"><i class="fas fa-times"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form @submit="save">
                    <div class="modal-body">
                        <div class="ajax-response">
                            <div class="ajax-message"></div>
                        </div>
                        <input type="hidden" v-model="nid" name="nid">
                        <div class="form-group">
                            <label>Role</label>
                            <select v-model="role_id" name="role_id" class="form-control">
                                <option value="" disabled selected>Select Role</option>
                                <option v-for="role in roles" :value="role.id">{{ role.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" v-model="name" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" v-model="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" v-model="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                users: [],
                roles: [],
                nid: 0,
                role_id: 0,
                name: '',
                email: '',
                password: ''
            }
        },
        mounted() {
            axios.get('usersapi/get-users').then(response => {
                this.users = response.data;
            });

            axios.get('rolesapi/get-roles').then(response => {
                this.roles = response.data;
            });
        },
        methods: {
            save(e) {
                e.preventDefault();
                let data = {
                    nid: this.nid,
                    role_id: this.role_id,
                    name: this.name,
                    email: this.email,
                    password: this.password
                };
                axios.post('usersapi/save', data).then(response => {

                    if(response.data.status){

                        $('.ajax-response').show();
                        $('.ajax-message').html(response.data.message);

                        setTimeout(function(){
                            $('.ajax-response').fadeOut();
                            $('#myModal').modal('hide');
                        },2000);

                        if(response.data.status1 == 'saved'){

                            Vue.set(this.users, this.users.length, response.data.user);

                        }else{
                            this.users.forEach((value, key) => {
                                if(response.data.user.id == value.id){
                                    Vue.set(this.users, key, response.data.user);
                                    return;
                                }
                            });
                        }

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
            },
            open() {
                $('#myModal').modal('show');

                this.nid = 0;
                this.role_id = 0;
                this.name = '';
                this.email = '';
                this.password = '';
            },
            search(data) {
                $('#myModal').modal('show');

                this.nid = data.id;
                this.role_id = data.role_id;
                this.name = data.name;
                this.email = data.email;
                this.password = data.password;
            },
            delete1(data) {

                bootbox.confirm('Do you want to delete this user?', function(e){
                    if(e){
                        axios.post('usersapi/delete', data).then(response => {
                            if(response.data.status){
                                this.users.forEach((value, key) => {
                                    if(response.data.user.id == value.id){
                                        this.users.splice(key, 1);
                                    }
                                });
                            }

                            bootbox.alert(response.data.message);
                        });
                    }
                }.bind(this));
        
            }
        }
    }
</script>
