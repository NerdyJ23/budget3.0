<template>
  <v-app>
    <v-content>
      <Navbar></Navbar>
      <v-divider></v-divider>
      <router-view></router-view>
    </v-content>
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

