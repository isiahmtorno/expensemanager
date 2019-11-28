<template>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Expense Category</span>
                <h3 class="page-title">List of Expense Categories</h3>
            </div>
        </div>

        <button class="btn btn-primary mb-3" @click="open">Add Category <i class="fas fa-plus ml-2"></i></button>
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th width="30%">Name</th>
                    <th width="50%">Description</th>
                    <th width="20%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories">
                    <td>{{ category.name }}</td>
                    <td>{{ category.description }}</td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-secondary" @click="search(category)"><i class="fas fa-pencil-alt"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger" @click="delete1(category)"><i class="fas fa-times"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit="save">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="ajax-response">
                            <div class="ajax-message"></div>
                        </div>
                        <input type="hidden" v-model="nid" name="nid">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" v-model="name" name="name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Category Description</label>
                            <textarea v-model="description" name="description" class="form-control" rows="4"></textarea>
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
        data() {
            return {
                categories: [],
                nid: 0,
                name: '',
                description: ''
            }
        },
        mounted() {
            axios.get('categoryapi/get').then(response => {
                this.categories = response.data;
            });
        },
        methods: {
            save(e) {
                e.preventDefault();
                let data = {
                    nid: this.nid,
                    name: this.name,
                    description: this.description
                };
                axios.post('categoryapi/save', data).then(response => {

                    if(response.data.status){

                        $('.ajax-response').show();
                        $('.ajax-message').html(response.data.message);

                        setTimeout(function(){
                            $('.ajax-response').fadeOut();
                            $('#myModal').modal('hide');
                        },2000);

                        if(response.data.status1 == 'saved'){

                            Vue.set(this.categories, this.categories.length, response.data.category);

                        }else{
                            this.categories.forEach((value, key) => {
                                if(response.data.category.id == value.id){
                                    Vue.set(this.categories, key, response.data.category);
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
                this.name = '';
                this.description = '';
            },
            search(data) {
                $('#myModal').modal('show');

                this.nid = data.id;
                this.name = data.name;
                this.description = data.description;
            },
            delete1(data) {

                bootbox.confirm('Do you want to delete this category?', function(e){
                    if(e){
                        axios.post('categoryapi/delete', data).then(response => {
                            if(response.data.status){
                                this.categories.forEach((value, key) => {
                                    if(response.data.category.id == value.id){
                                        this.categories.splice(key, 1);
                                    }
                                });

                                bootbox.alert(response.data.message);
                            }
                        });
                    }
                }.bind(this));
        
            }
        }
    }
</script>
