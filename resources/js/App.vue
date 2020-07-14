<template>
  <div>
    <header>
      <Navbar />
    </header>
    <Sidebar v-if="isLogin"></Sidebar>
    <main>
      <div class="container">
        <RouterView />
      </div>
    </main>
    <footer>
      <Footer />
    </footer>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
import Sidebar from "./components/Sidebar.vue";
import { INTERNAL_SERVER_ERROR } from './util'

export default {
  components: {
    Navbar,
    Footer,
    Sidebar
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