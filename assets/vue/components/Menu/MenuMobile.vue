<template>
    <div class="ui padded middle aligned centered grid" id="nav-v">
        <div class="row  four column wide toolbar">
            <div class="left floated column  ">
                <img src="./logo.png"  class="ui medium image"/>
            </div>
            <div class="right floated column" >
                <div class="middle aligned content" >


                       <!--<sui-button basic color="black" icon="bars" @click.native="showMenu"  style="display: block ; cursor: pointer"/>-->
                    <a href="#" @click="showMenu" class="ui basic button" style="    padding: 20rem;
    display: contents;"> <i class="bars icon"></i> </a>
                </div>
            </div>
        </div>
        <div class="row menu-wrap"  v-bind:class="on ? 'menu-wrap-on' : ''" >
            <div class="ui fourteen wide column divided relaxed link list">
                <div class="item"><router-link :to="{name: 'home'}">Accueil</router-link></div>
                <div class="item"><router-link :to="{name: 'products'}">La Carte</router-link></div>
                <div class="item"><router-link :to="{name: 'photos'}">Photos</router-link></div>
                <div class="item">Horaires</div>
                <div class="item"><router-link :to="{name: 'contact'}">Contact</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"MenuMobile",
    data () {
        return {
            on: false
        }
    },
    watch:{
        $route (to, from){
            this.on = false;
        }
    },
    computed: {
    },
    methods: {
        showMenu: function (event) {
            if (event) event.preventDefault()
            {
                this.on = !this.on
            }

        }
    }
}
</script>

<style scoped>
    #nav-v{
        display:block;
    }
    .menu-wrap{
        opacity: 0 ;
        height: 0px ;
        transition: height linear .6s, opacity linear .8s ;
    }
    .menu-wrap-on{
        opacity: 1 !important;
        height: 200px !important; ;
    }
    #nav-v .bars{
        line-height: inherit;
        font-size: 2.5rem;
        cursor: pointer;
    }

</style>