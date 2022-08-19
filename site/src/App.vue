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

<style>
@font-face {
	font-family: "Ticket";
	src: local("Ticket"),
	url(./assets/fonts/Ticketing.ttf) format("truetype");
}

@font-face {
	font-family: "Coder-Font";
	src: local("Coder-Font"),
	url(./assets/fonts/Semi-Coder-Regular.otf) format("opentype");
}
.receipt {
	font-family: "Ticket";
	font-size: 24px;
}

.receipt-item {
	font-family: "Coder-Font";
	font-size: 24px;
}
</style>