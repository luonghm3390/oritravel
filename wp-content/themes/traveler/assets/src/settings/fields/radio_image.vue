<template>
    <vue-select-image :dataImages="dataImages" w="900" v-model="value" :is-multiple="false"
                      @onselectimage="onSelectImage" :selectedImages="idSelected">
    </vue-select-image>
</template>

<script>
    import { abstractField } from "vue-form-generator";
    import VueSelectImage from 'vue-select-image';

    export default {
        mixins: [ abstractField ],
        props: ['schema'],
        data(){
            return {
                dataImages: this.schema.choices,
                idSelected: []
            }
        },
        created(){
          this.idSelected.push(this.value);
        },
        methods:{
            onSelectImage: function (data) {
                this.idSelected = [];
                this.idSelected.push(data.id);
                this.value = data.id;
            },
        },
        components:{
            VueSelectImage
        }
    };
</script>