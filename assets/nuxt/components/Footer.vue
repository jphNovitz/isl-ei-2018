<template>
  <div id="footer" class="ui three column stackable grid">
    <div class="ui orange row">
      <div class="column center aligned">
        <h4 class="header">{{ infos.title }}</h4>
        <p>
          {{ infos.subTitle }} <br />
          TVA: {{ infos.tva }}
        </p>
      </div>
      <div class="column center aligned">
        <h4 class="header">Notre adresse</h4>
        <p>
          {{ infos.adress }} <br />
          {{ infos.city }}
        </p>
        <p>
          {{ infos.phone }} <br />
          {{ infos.email }}
        </p>
      </div>
      <div class="column center aligned">
        <h4 class="header">Réseaux sociaux</h4>
        <a
          :href="infos.socialFacebook"
          class="ui circular facebook icon button"
        >
          <i class="facebook icon"></i>
        </a>
        <a
          :href="infos.socialInstagram"
          class="ui circular instagram icon button"
        >
          <i class="instagram icon"></i>
        </a>
        <a
          :href="infos.socialInstagram"
          class="ui circular linkedin icon button"
        >
          <i class="linkedin icon"></i>
        </a>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  components: {},
  computed: {
    infos: function() {
      console.log(this.$store.getters['init/infos'])
      return this.$store.getters['init/infos']
    }
  },
  mounted() {
    /* this.$axios
      .$get('http://localhost:8000/api/privacy/public/infos')
      .then(function(response) {
        // handle success
        console.log(JSON.parse(response))
      }) */
  },
  methods: {}
}
</script>
<style scoped>
#footer {
  margin: 2rem 0 0 0 !important;
  font-size: 0.75rem;
}

.orange {
  border-top: solid 2px green;
}

@media screen and (min-width: 1024px) {
  #footer {
    font-size: 0.9rem;
  }
}
</style>
