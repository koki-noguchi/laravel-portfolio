<template>
  <v-app>
    <AfterLoginNavbar v-if="isLogin" />
    <Navbar v-else />
    <v-main class="amber lighten-5">
      <v-container fluid>
        <v-row justify="start">
          <Message
            class="ml-5"
            style="position: fixed; top: 6%; z-index: 100;" />
        </v-row>
        <v-row
          align="center"
          justify="center"
        >
          <v-col
            cols="12"
            sm="10"
            md="10"
          >
            <RouterView class="mt-5" />
          </v-col>
        </v-row>
      </v-container>
    </v-main>
    <v-footer padless class="mb-12 mb-lg-0">
      <Footer />
    </v-footer>
    <FooterResponsive class="hidden-lg-and-up" />
  </v-app>
</template>

<script>
import Navbar from './components/Navbar.vue'
import AfterLoginNavbar from './components/AfterLoginNavbar.vue'
import Footer from './components/Footer.vue'
import FooterResponsive from './components/FooterResponsive.vue'
import Message from './components/Message.vue'
import { NOT_FOUND, UNAUTHORIZED, INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Navbar,
    AfterLoginNavbar,
    Footer,
    FooterResponsive,
    Message
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
      async handler (val) {
        if (val === INTERNAL_SERVER_ERROR) {
          this.$router.push('/500')
        } else if (val === UNAUTHORIZED) {
          await axios.get('/api/refresh-token')

          this.$store.commit('/auth.setUser', null)
          this.$router.push('/login')
        } else if (val === NOT_FOUND) {
          this.$$router.push('/not-found')
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