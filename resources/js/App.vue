<template>
  <v-app>
    <AfterLoginNavbar v-if="isLogin" />
    <Navbar v-else />
    <v-main>
      <v-container fluid>
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="8"
            md="4"
          >
            <RouterView class="mt-5" />
          </v-col>
        </v-row>
      </v-container>
    </v-main>
    <v-footer padless>
      <Footer />
    </v-footer>
    <FooterResponsive class="hidden-md-and-up" />
  </v-app>
</template>

<script>
import Navbar from './components/Navbar.vue'
import AfterLoginNavbar from './components/AfterLoginNavbar.vue'
import Footer from './components/Footer.vue'
import FooterResponsive from './components/FooterResponsive.vue'
import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Navbar,
    AfterLoginNavbar,
    Footer,
    FooterResponsive
  },
  computed: {
    errorCode () {
      return this.$store.state.error.code
    },
    isLogin () {
      return this.$store.getters['auth/check']
    }
  },
  watch: {
    errorCode: {
      handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        }
      },
      immediate: true
    },
    $route () {
      this.$store.commit('error/setCode', null)
    }
  }
}
</script>