<template>
    <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
        <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
                <a class="navbar-brand w-100 mr-0" href="javascript:void(0);" style="line-height: 25px;">
                    <div class="d-table m-auto">
                        <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="images/logo.svg">
                        <span class="d-none d-md-inline ml-1">Expense Manager</span>
                    </div>
                </a>
                <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                    <i class="material-icons">&#xE5C4;</i>
                </a>
            </nav>
        </div>
        <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
            <div class="input-group input-group-seamless ml-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search">
            </div>
        </form>
        <div class="nav-wrapper">
            <h6 class="main-sidebar__nav-title">Dashboards</h6>
            <ul class="nav nav--no-borders flex-column">
                <li class="nav-item">
                    <router-link to="/dashboard" class="nav-link" exact-active-class="active" tag="a">
                        <i class="material-icons">edit</i>
                        <span>Dashboard</span>
                    </router-link>
                </li>
            </ul>
            <div v-if="user.role.name === 'admin'">
                <h6 class="main-sidebar__nav-title">User Management</h6>
                <ul class="nav nav--no-borders flex-column">
                    <li class="nav-item">
                        <router-link to="/users" class="nav-link" exact-active-class="active" tag="a">
                            <i class="material-icons">person</i>
                            <span>Users</span>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/roles" class="nav-link" exact-active-class="active" tag="a">
                            <i class="material-icons">list</i>
                            <span>Roles</span>
                        </router-link>
                    </li>
                </ul>
            </div>
            <h6 class="main-sidebar__nav-title">Expense Management</h6>
            <ul class="nav nav--no-borders flex-column">
                <li class="nav-item" v-if="user.role.name === 'admin'">
                    <router-link to="/expense-category" class="nav-link" exact-active-class="active" tag="a">
                        <i class="material-icons">list_alt</i>
                        <span>Expense Category</span>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/expenses" class="nav-link" exact-active-class="active" tag="a">
                        <i class="material-icons">attach_money</i>
                        <span>Expenses</span>
                    </router-link>
                </li>
            </ul>
        </div>
    </aside>
</template>

<script>
    export default {
        props: ['user'],
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
