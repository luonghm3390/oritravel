<template>
    <v-select label="name" :filterable="false" :options="options" @search="onSearch" v-on="onChange()" v-model="selected">
        <template slot="no-options">
            {{ typingText }}
        </template>
        <template slot="option" slot-scope="option">
            <div class="d-center" v-html="option.name"></div>
        </template>
        <template slot="selected-option" slot-scope="option">
            <div class="selected d-center" v-html="option.name"></div>
        </template>
    </v-select>
</template>

<script>
    import { abstractField } from "vue-form-generator";
    import vSelect from 'vue-select';
    import API from '../api';

    export default {
        props: ['schema'],
        data(){
            return {
                typingText: traveler_settings.i18n.typing,
                options: [],
                selected: {id: this.value, name: this.schema.sld},
            }
        },
        methods:{
            onSearch(search, loading) {
                loading(true);
                let app=this;
                API.postSelectAjax(search, this.schema.post_type, this.schema.sparam).then(function (rs) {
                    let body=rs.data;
                    app.options=body.rows;
                    loading(false);
                })
            },
            onChange(){
                if (this.selected !== null) {
                    this.value = this.selected.id;
                }else{
                    this.value = '';
                }
            },
        },
        components:{
            "v-select": vSelect,
        },
        mixins: [ abstractField ],
    };
</script>