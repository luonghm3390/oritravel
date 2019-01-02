<template>
    <div class="">
        <div class="mapping-currency">
            <div class="row-mapping">
                <div class="col-mapping-lang mapping-lang-head">{{ i18n.language }}</div>
                <div class="col-mapping-currency mapping-currency-head">{{ i18n.defaultCurrency }}</div>
            </div>
            <div class="row-mapping-content" :data-lang-code="lang.language_code" v-for="(lang, key, index) in langs">
                <div class="col-mapping-lang">
                    <img :src="lang.country_flag_url" class="img-responsive"> {{ lang.native_name }} ( {{ lang.language_code }} )</div>
                <div class="col-mapping-currency">
                    <div class="">
                        <select name="" class="mapping-currency-list">
                            <option value="-1">--- {{ i18n.selectCurrency }} ---</option>
                            <option :selected="(currentCurrency(index, lang.language_code, currency.name))" v-for="(currency, ii) in list_currency" :value="currency.name" >{{ currency.name }}</option>
                        </select>
                        <span class="spinner mapping-loading"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { abstractField } from "vue-form-generator";

    export default {
        mixins: [ abstractField ],
        data(){
            return {
                i18n:traveler_settings.i18n,
                langs: this.schema.sdata.langs,
                list_currency: this.schema.sdata.list_currency,
                mapping_currency: this.schema.sdata.mapping_currency
            }
        },
        methods:{
            currentCurrency(i, lang_code, current_name){
                var cur_option = '';
                if(this.mapping_currency != false){
                    var cur = this.mapping_currency[i][0];
                    if (cur == lang_code) {
                        cur_option = this.mapping_currency[i][1];
                    }
                }
                if(current_name == cur_option)
                    return true;
                else
                    return false;
            },
        },
    };

    (function ($) {
        $(document).on('change', '.mapping-currency-list', function (e) {
            $(this).next().addClass('mapping-loading-visible');
            changeMappingSelect();
        });
        function changeMappingSelect() {
            var dataMapping = [];
            $('.mapping-currency .row-mapping-content').each(function(){
                var dataItemLang = $(this).data('lang-code');
                var dataItemCurrency = $(this).find('select').val();
                dataMapping.push([dataItemLang, dataItemCurrency]);
            });

            $.ajax({
                method: "POST",
                dataType: "json",
                url: ajaxurl,
                data: {
                    data_mapping: dataMapping,
                    action: 'st_mapping_currency'
                },
                beforeSend: function () {

                },
                success: function (response) {
                    if($('.mapping-loading').hasClass('mapping-loading-visible')){
                        $('.mapping-loading').removeClass('mapping-loading-visible');
                    }
                    return false;
                }
            });
        }
    })(jQuery)
</script>