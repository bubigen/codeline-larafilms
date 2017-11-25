<template>
    <form enctype="multipart/form-data" v-on:submit.prevent="update($event)" ref="form">
        <div class="row">
            <div class="col-md-5 form-group">
                <label for="name" class="form-control-label">Name:</label>
                <input type="text" name="name" class="form-control" id="name" v-model="film.name" v-validate="'required'">
                <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
            </div>

            <div class="col-md-3 form-group">
                <label for="ticket_price" class="form-control-label">Ticket Price:</label>
                <input type="text" name="ticket_price" class="form-control" id="name" v-model="film.ticket_price" v-validate="'required|numeric'">
                <span v-show="errors.has('ticket_price')" class="help is-danger">{{ errors.first('ticket_price') }}</span>
            </div>

            <div class="col-md-4 form-group">
                <label for="country" class="form-control-label">Country:</label>
                <input type="text" name="country" class="form-control" id="name" v-model="film.country" v-validate="'required'">
                <span v-show="errors.has('country')" class="help is-danger">{{ errors.first('country') }}</span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 form-group">
                <label for="rating" class="form-control-label">Rating:</label>
                <input type="text" name="rating" class="form-control" id="rating" v-model="film.rating" v-validate="'required|min_value:1|max_value:5'">
                <span v-show="errors.has('rating')" class="help is-danger">{{ errors.first('rating') }}</span>
            </div>

            <div class="col-md-3 form-group">
                <label for="release_date" class="form-control-label">Release Date:</label>
                <input type="text" name="release_date" class="form-control" id="release_date" v-model="film.release_date" v-validate="'required|date_format:YYYY-MM-DD'">
                <span v-show="errors.has('release_date')" class="help is-danger">{{ errors.first('release_date') }}</span>
            </div>

            <div class="col-md-4 form-group">
                <label for="photo" class="form-control-label">Photo:</label>
                <input type="file" name="photo" class="form-control" id="photo" accept="image/*" v-validate="'required'" ref="photoInput" v-on:change="uploadPhoto" >
                <span v-show="errors.has('photo')" class="help is-danger">{{ errors.first('photo') }}</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="description" class="form-control-label">Description:</label>
            <textarea class="form-control" id="description" name="description" v-model="film.description" v-validate="'required'"></textarea>
            <span v-show="errors.has('description')" class="help is-danger">{{ errors.first('description') }}</span>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-secondary" v-on:click="close()">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</template>

<script>
    import Hub from '../events/Hub';
    export default {
        name: 'FilmForm',
        /*props: {
            'modaldata': {
                type: Object,
                default: () => {}
            }
        },*/
        props: ['film'],
        data: function() {
            return {
                formData: new FormData()
            };
        },
        methods: {
            update: function(e) {
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        for ( var key in this.film ) {
                            if(key == 'photo') continue;
                            this.formData.append(key, this.film[key]);
                        }
                        Hub.$emit(this.film.emit, this.formData);
                        return;
                    }
                    alert('Please correct the errors!');
                });
            },

            close: function() {
                Hub.$emit('close-product-form')
            },

            uploadPhoto(event) {
                this.formData.append('photo', event.target.files[0], event.target.files[0].filename);
            },

            checkErrors() {
                console.log(this.errors.any());
            }
        },

        mounted: function() {
            this.$refs.photoInput.value = null; 
        },
    }
</script>
