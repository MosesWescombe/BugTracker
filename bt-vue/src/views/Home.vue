<template>
   <div>
      <Main />
      <Footer />
   </div>
</template>

<script>
import Main from '../components/Main.vue';
import Footer from '../components/Footer.vue';

export default {
   name: 'Home',

   components: {
      Main,
      Footer,
   },
};
</script>
