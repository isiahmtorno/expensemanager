<template>
	<div v-if="!user">
		<login @user="getUser"></login>
	</div>
    <div v-else>
    	<div class="container-fluid">
			<div class="row">
				<navigation :user="user" @user="getUser"></navigation>
				<main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
					<header1 :user="user" @user="getUser"></header1>
					<router-view></router-view>
				</main>
			</div>
		</div>
    </div>
</template>

<script>
    export default {
    	data() {
          	return{
            	user: null
          	}
        },
        mounted() {
            axios.get('usersapi/get-user').then(response => {

	            this.user = response.data.user;
            });
        },
        methods: {
        	getUser(response){
        		this.user = response;
        	}
        }
    }
</script>

		