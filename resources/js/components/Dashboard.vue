<template>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header row no-gutters py-4">
            <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Dashboard</span>
                <h3 class="page-title">Dashboard</h3>
            </div>
        </div>

        <canvas height="180" id="chart" class="m-auto"></canvas>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                expenses: [],
                label: [],
                data: [],
            }
        },
        mounted() {
            axios.get('expensesapi/chart').then(response => {
                this.expenses = response.data;
            });
        },
        watch: {
            expenses: function(new_value, old_value){
                this.getChart();
            }
        },
        methods: {
            getChart() {
                this.expenses.forEach((value, key) => {
                    Vue.set(this.label, this.label.length, value.name);
                    Vue.set(this.data, this.data.length, value.total.toFixed(2));
                });

                function randomColor() {
                    var num = Math.round(0xffffff * Math.random());
                    var r = num >> 16;
                    var g = num >> 8 & 255;
                    var b = num & 255;
                    return 'rgb(' + r + ', ' + g + ', ' + b + ','+ .6 +')';
                }
                new Chart($('#chart'), {
                    type: 'pie',
                    data: {
                        labels: this.label,
                        datasets: [{
                            label: 'Total Expenses',
                            data: this.data,
                            backgroundColor: [
                               randomColor(),
                               randomColor(),
                               randomColor(),
                               randomColor(),
                               randomColor(),
                               randomColor(),
                               randomColor(),
                               randomColor(),
                               randomColor()
                            ]
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            }
        }
    }
</script>
