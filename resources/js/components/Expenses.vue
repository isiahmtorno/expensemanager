<template>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Expenses</span>
                <h3 class="page-title">List of Expenses</h3>
            </div>
        </div>

        <button class="btn btn-primary mb-3" @click="open">Add Expenses <i class="fas fa-plus ml-2"></i></button>
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th width="30%">Category</th>
                    <th width="25%">Amount</th>
                    <th width="25%">Date</th>
                    <th width="20%">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="expense in expenses">
                    <td>{{ expense.category.name }}</td>
                    <td>{{ expense.amount }}</td>
                    <td>{{ expense.date }}</td>
                    <td>
                        <a href="javascript:void(0);" class="btn btn-secondary" @click="search(expense)"><i class="fas fa-pencil-alt"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger" @click="delete1(expense)"><i class="fas fa-times"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit="save">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Expense</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="ajax-response">
                            <div class="ajax-message"></div>
                        </div>
                        <input type="hidden" v-model="nid" name="nid">
                        <div class="form-group">
                            <label>Category</label>
                            <select v-model="category_id" name="category_id" class="form-control">
                                <option value="" disabled selected>Select Category</option>
                                <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" v-model="amount" name="amount" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" v-model="date" name="date" class="form-control">
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
                expenses: [],
                nid: 0,
                category_id: 0,
                amount: 0,
                date: ''
            }
        },
        mounted() {
            axios.get('categoryapi/get').then(response => {
                this.categories = response.data;
            });

            axios.get('expensesapi/get').then(response => {
                this.expenses = response.data;
            });
        },
        methods: {
            save(e) {
                e.preventDefault();
                let data = {
                    nid: this.nid,
                    category_id: this.category_id,
                    amount: this.amount,
                    date: this.date
                };
                axios.post('expensesapi/save', data).then(response => {

                    if(response.data.status){

                        $('.ajax-response').show();
                        $('.ajax-message').html(response.data.message);

                        setTimeout(function(){
                            $('.ajax-response').fadeOut();
                            $('#myModal').modal('hide');
                        },2000);

                        if(response.data.status1 == 'saved'){

                            Vue.set(this.expenses, this.expenses.length, response.data.expense);

                        }else{
                            this.expenses.forEach((value, key) => {
                                if(response.data.expense.id == value.id){
                                    Vue.set(this.expenses, key, response.data.expense);
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
                this.category_id = 0;
                this.amount = 0;
                this.date = '';
            },
            search(data) {
                $('#myModal').modal('show');

                this.nid = data.id;
                this.category_id = data.category_id;
                this.amount = data.amount;
                this.date = data.date;
            },
            delete1(data) {

                bootbox.confirm('Do you want to delete this expenses?', function(e){
                    if(e){
                        axios.post('expensesapi/delete', data).then(response => {
                            if(response.data.status){
                                this.expenses.forEach((value, key) => {
                                    if(response.data.expense.id == value.id){
                                        this.expenses.splice(key, 1);
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
