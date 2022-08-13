<template>
  <v-app>
    <v-main>
      <Navbar></Navbar>
      <v-divider></v-divider>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
import Navbar from './components/Utility/Nav';

export default {
  name: 'App',
  components: {
	Navbar
  }
}
</script>

