<template>
    <div class="col-md-offset-2 col-md-8">
        <div class="list-wrap" v-show="!isEdit">
            <div class="row pull-right"><button type="button" class="btn btn-primary" v-on:click="create()">Create</button></div>
            <div class="well well-sm" v-for="film in list">
                <div class="row">
                    <div class="col-xs-3 col-md-3 text-center">
                        <img v-bind:src="film.photo" v-bind:alt="film.name"
                            class="img-rounded img-responsive" />
                    </div>
                    <div class="col-xs-9 col-md-9 section-box">
                        <h2>
                            <a v-bind:href="'films/'+film.slug">{{ film.name }} </a>
                        </h2>
                        <p>
                            {{ film.description }}</p>
                        <hr />
                        <div class="row rating-desc">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-heart" v-for="index in Number(film.rating)"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <v-paginator :options="options" :resource_url="resource_url" @update="updateResource"></v-paginator>
        </div>
        <div class="product-form" v-show="isEdit">
            <film-form v-bind:film="film"></film-form>
        </div>
    </div>
</template>

<script>
    import Hub from '../events/Hub';
    import FilmForm from './FilmForm.vue'
    //import DeleteComponent from './DeleteComponent.vue'
    import VuePaginator from 'vuejs-paginator'
    export default {
        name: 'Film',
        components: {
            //ProductForm,
            //DeleteComponent,
            VPaginator: VuePaginator
        },
        data: function() {
            return {
                isEdit: false,
                list: [],
                genre: [],
                film: {
                    id: '',
                    name: '',
                    description: '',
                    rating: '',
                    country: [],
                    year: '',
                    ticket_price: '',
                    photo: '',
                    release_date: '',
                    slug: '',
                    emit: ''
                },
                resource_url: 'api/films',
                options: {
                    remote_data: 'data',
                    remote_current_page: 'current_page',
                    remote_last_page: 'last_page',
                    remote_next_page_url: 'next_page_url',
                    remote_prev_page_url: 'prev_page_url',
                    next_button_text: 'Next',
                    previous_button_text: 'Prev'
                }
            };
        },

        mounted: function() {
            //this.fetch();
            //this.fetchAttributes()
            //this.fetchCategories()
            Hub.$on('create-film', this.store);
            Hub.$on('close-product-form', this.closeForm);
        },

        methods: {
            fetch: function() {
                var films = this;
                window.axios.get('api/films').then(function (response) {
                    films.list = response.data.data
                });
            },

            fetchAttributes: function() {
                var cmp = this;
                window.axios.get(this.$parent.appGlobal.base_url+'/api/attributes').then(function (response) {
                    cmp.attributes = response.data.data
                });
            },

            fetchCategories: function() {
                var cmp = this;
                window.axios.get(this.$parent.appGlobal.base_url+'/api/categories').then(function (response) {
                    cmp.categories = response.data.data
                });
            },

            create: function() {
                this.isEdit = true
                this.film.emit = 'create-film'
            },

            store: function (film) {
                var cmp = this;
                for (var pair of film.entries()) {
                    console.log(pair[1]); 
                }
                window.axios.post('api/films', film, {headers: {'Content-Type': 'multipart/form-data'}}).then(function(response){
                    cmp.isEdit = false
                    cmp.product.emit = ''
                    cmp.fetch()
                    Hub.$emit('close-modal');
                    Hub.$emit("set-alert", "alert-success", response.data.meta.message, true)
                }).catch(function(error) {
                    if(error.response && error.response.status != 200) {
                        Hub.$emit("set-alert", "alert-danger", error.response.statusText, true)
                    }
                })
            },

            update: function() {
                var cmp = this;
                window.axios.patch('api/products/' + cmp.product.id, {
                    title: cmp.product.title,
                    subtitle: cmp.product.subtitle,
                    description: cmp.product.description,
                    attributes: cmp.product.attribute_values
                }).then(function(response){
                    cmp.isEdit = false
                    Hub.$emit("set-alert", "alert-success", response.data.meta.message, true)
                    cmp.product.emit = ''
                    cmp.fetch()
                    Hub.$emit('close-modal');
                }).catch(function(error) {
                    if(error.response && error.response.status != 200) {
                        Hub.$emit("set-alert", "alert-danger", error.response.statusText, true)
                    }
                })
            },

            edit: function(id) {
                var cmp = this;
                window.axios.get(this.$parent.appGlobal.base_url+'/api/products/' + id + '/edit').then(function(response){
                    cmp.product.id = response.data.data.id;
                    cmp.product.title = response.data.data.title
                    cmp.product.subtitle = response.data.data.subtitle
                    cmp.product.description = response.data.data.description
                    cmp.product.attributes = response.data.data.attributes
                    cmp.product.attribute_values = response.data.data.attribute_values
                    cmp.product.category_values = response.data.data.category_values[0]
                    cmp.isEdit = true
                    cmp.product.emit = 'update-product'
                    //Hub.$emit('set-modal-data', category, 'Edit Category', EditCategoryModal);
                    //Hub.$emit('open-modal');
                })
            },

            deleteCategory: function (id) {
                var cmp = this;
                var category = {id: id, type: 'category'};
                Hub.$emit('set-modal-data', category, 'Delete Category', DeleteComponent);
                Hub.$emit('open-modal');
                /* window.axios.delete('api/categories/' + id).then(function(response){
                    cmp.fetch()
                }) */
            },

            deleteItem: function(category) {
                var cmp = this;
                window.axios.delete('api/categories/' + category.id).then(function(response){
                    cmp.fetch()
                    Hub.$emit('close-modal');
                    Hub.$emit("set-alert", "alert-success", response.data.meta.message, true)
                }).catch(function(error) {
                    if(error.response && error.response.status != 200) {
                        Hub.$emit("set-alert", "alert-danger", error.response.statusText, true)
                    }
                })
            },

            closeForm: function() {
                this.isEdit = false;
                this.resetForm()
            },

            resetForm: function() {
                this.product = {
                    id: '',
                    title: '',
                    subtitle: '',
                    description: ''
                }
            },

            updateResource(data){
                this.list = data
            }
        }
    }
</script>
