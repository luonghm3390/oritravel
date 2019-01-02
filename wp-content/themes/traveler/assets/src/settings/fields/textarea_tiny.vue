<template>
    <vue-mce v-model="value" :config="config"></vue-mce>
</template>

<script>
    import { abstractField } from "vue-form-generator";
    import { component } from 'vue-mce';

    export default {
        mixins: [ abstractField ],
        props: ['schema'],
        data(){
            return {
                config: {
                    height: 500,
                    inline: false,
                    theme: 'modern',
                    fontsize_formats: "8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 34px 38px 42px 48px 54px 60px",
                    plugins: 'code visualblocks visualchars image link media table hr anchor insertdatetime textcolor colorpicker',
                },
            }
        },
        components: {
            'vue-mce': component,
        },
    };
</script>