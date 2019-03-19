/**
 * ingredient add via api management
 * author: Novitz Jean-Philippe <novitz@gmail.com>
 * date: february 2019
 */

import vue from 'vue';
import VueResource from 'vue-resource'
import vuePdf from 'vue-pdf'

const images = Vue.component('images', {

    delimiters: ['${', '}'],
    template: `
    <div>                
        <div class="ui card fluid centered">
            <div class="content">
                <div class="header">
                    Produit
                </div>
                <div class="ui medium centered image ">
                    <template v-if="product.image_url">
                        <img :src="product.image_url" class="ui centered image">
                    </template>
                    <template v-else>
                    <img src="../../../images/no-photo.png" class="ui  image">
                    </template>
                </div>
                    <!--</div>-->
                <div class="ui card fluid centered">
                    <div class="content">
                        <div class="header">
                            Ingredients
                        </div>
                        <div class="ui medium centered image">
                            <template v-if="product.image_ingredients_url">
                                <img :src="product.image_ingredients_url" class="ui image">
                            </template>
                            <template v-else>
                                <img src="../../../images/no-photo.png" class="ui  image">
                            </template>
                        </div>
                    </div>
                </div>
                <div class="ui card fluid centered">
                <div class="content">
                    <div class="header">
                        Nutrition
                    </div>
                    <div class="ui medium image ">
                        <template v-if="product.image_nutrition_url">
                            <img :src="product.image_nutrition_url" class="ui image">
                        </template>
                        <template v-else>
                            <img src="../../../images/no-photo.png" class="ui  image">
                        </template>
                    </div>
                </div>
            </div>
    </div>
        </div>
    </div>
`,
    props: ['product'],
    data: function () {
        return {}
    },
    mounted() {
    }
});

const pdf = Vue.component('pdf', {
    components: {
        'vue-pdf': vuePdf
    },
    template: `
        <div>
        <a class="fluid ui bg-info button"
            :href="'/admin/pdf/ingredient/view/' + slug"
            target="_blank">ouvrir dans une nouvelle fenêtre
        </a>
            <vue-pdf :src="'../../../pdf/documents/ingredients/' + slug + '.pdf'"> </vue-pdf>
        </div>`,
    props: ['slug'],
    data: function () {
        return {}
    },
    mounted() {
    }
});

new Vue({
    el: '#ingredients-api',
    delimiters: ['${', '}'],
    components: {
        'pdf': pdf,
        'images': images
    },
    data: function () {
        return {
            code: '',
            product: {},
            messages: [],
            pdf: {
                lastSLUG: -1,
                isActive: false
            },
            righte: 'images',
            leftPanel: 'general'
        }
    },
    mounted() {
    },
    computed: {
        pdfIcon: function () {
            if (this.righte === 'pdf') {
                return 'images'
            } else {
                return 'pdf'
            }
        },
        objectDisabled: function () {
            if (Object.keys(this.product).length === 0) {
                return 'disabled'
            }
        }
    },
    methods: {
        resetInfos: function () {
            this.code = ''
            this.product = {}
            this.message = {}
        },
        getInfos: function () {
            var product = {}
            this.$http.get('https://fr.openfoodfacts.org/api/v0/produit/' + this.code + '.json').then(response => {
                if (response.data.status === 1) {
                    let raw = response.data.product
                    console.log(response.data.product)
                    product.code = raw.code
                    product.brands = raw.brands
                    product.generic_name_fr = raw.generic_name_fr
                    product.name = raw.product_name
                    product.image_ingredients_url = raw.image_ingredients_url
                    product.image_nutrition_url = raw.image_nutrition_url
                    product.image_url = raw.image_url
                    product.ingredients_text_fr = raw.ingredients_text_fr
                    if (raw.allergens_tags.length > 0) {
                        let en_allergens = ['crustaceans', 'eggs', 'fish', 'peanuts',
                            'soybeans', 'milk', 'nuts', 'celery', 'mustard', 'sesame seeds', 'sulphites', 'lupin', 'molluscs']
                        let fr_allergens = ['crustacés', 'oeufs', 'poisson', 'cacahuètes', 'soja', 'lait', 'noix', 'celeri',
                            'moutarde', 'graines de césame', 'sulphites', 'lupin', 'mollusques']
                        let allergens = []
                        raw.allergens_tags.forEach(tag => {
                            tag = tag.replace('en:', '')
                            tag = tag.replace('fr:', '')
                            let pos = en_allergens.indexOf(tag)
                            if (pos > -1) {
                                tag = fr_allergens[pos]
                            }
                            allergens.push(tag.trim())
                        })

                        product.allergens_tags = allergens
                    }

                    product.nutrition_grade_fr = raw.nutrition_grade_fr
                    // allergens_from_ingredients is a string
                    // i need to split to an array
                    if (raw.allergens_from_ingredients !== "") {
                        let temp = raw.allergens_from_ingredients.split(',')
                        let out = []
                        temp.map(item => {
                            if (out.indexOf(item) === -1) {
                                out.push(item.trim())
                            }
                        })
                        product.allergens_from_ingredients = out
                    } else {
                        product.allergens_from_ingredients = []
                    }
                    if (raw.categories !== "" && raw.categories !== undefined) {
                        product.categories = raw.categories.split(',')
                    } else {
                        product.categories = []
                    }

                    product.countries = raw.countries
                    product.additives_tags = []
                    raw.additives_tags.map(tag => {
                        product.additives_tags.push(tag.replace('en:', ''))
                    })
                    // nutrient needs to be translated
                    let levels = raw.nutrient_levels
                    let temp = {}
                    temp.sucre = levels.sugars
                    temp.graisse = levels.fat
                    temp.sel = levels.salt
                    temp.graisseSaturee = levels['saturated-fat']
                    let trans = {
                        'low': 'faible',
                        'moderate': 'moyen',
                        'high': 'élevé'
                    }

                    Object.keys(temp).map(level => {
                        temp[level] = trans[temp[level]]
                    })
                    product.nutrient_levels = temp

                    // product.nutrition_grade_fr = raw.nutrition_grade_fr
                }
            })
            setTimeout(() => {
                this.product = product
            }, 200)
        },
        sendInfos: function () {
            this.messages = []
            // console.log(JSON.stringify(this.product))
            let packed = JSON.stringify(this.product)
            this.$http.post('/api/ingredient/new', packed, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }}).then(response => {
                let data = JSON.parse(response.data)
                let parsed = JSON.parse(data)
                this.resetInfos()
                if (response.status === 200) {
                    /* manage message*/
                    let persist_message = {}
                    persist_message.text = parsed.persist.message
                    persist_message.color = parsed.persist.result
                    this.messages.push(persist_message)

                    let pdf_message = {}
                    pdf_message.text = parsed.pdf.message
                    pdf_message.color = parsed.pdf.result
                    this.messages.push(pdf_message)

                    if (parsed.pdf.result === 'success') {
                        this.pdf.lastSLUG = parsed.persist.slug
                        this.pdf.isActive = true
                    }
                } else {
                    /* manage message*/
                    let persist_message = {}
                    persist_message.text = parsed.persist.message
                    persist_message.color = parsed.persist.result
                    this.message.push(persist_message)
                }
            })
        },
        switchPdf: function () {
            if (this.righte === 'pdf') {
                this.righte = 'images'
            } else if (this.righte === 'images') {
                this.righte = 'pdf'
            }

        },
        nutritionActive: function (letter) {
            if (this.product.nutrition_grade_fr !== letter) {
                return 'basic'
            }
        },
        setNutriGrade: function (letter) {
            this.product.nutrition_grade_fr = letter
        },
        setLeftPanel: function (panel) {
            this.leftPanel = panel
        }
    }

})
