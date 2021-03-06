<template>
   <v-app id="inspire">
      <v-app-bar app color="white" flat>
         <v-avatar
            :color="$vuetify.breakpoint.smAndDown ? 'black' : 'transparent'"
            size="32"
         ></v-avatar>

         <v-tabs centered style="margin-left: 72px;" color="black">
            <v-tab @click="changeTab(link)" v-for="link in links" :key="link">
               {{ link }}
            </v-tab>
         </v-tabs>

         <v-avatar
            style="margin-left: 5px;"
            size="45"
            color="white"
            v-if="!$auth.isAuthenticated"
         >
         </v-avatar>
         <!-- Check that the SDK client is not currently loading before accessing is methods -->
         <div v-if="!$auth.loading">
            <!-- show login when not authenticated -->
            <v-btn text v-if="!$auth.isAuthenticated" @click="login"
               >Log in</v-btn
            >
            <!-- show logout when authenticated -->
            <v-btn text v-if="$auth.isAuthenticated" @click="logout">
               Log out
            </v-btn>
         </div>
         <v-avatar
            style="margin-left: 5px;"
            size="45"
            color="white"
            v-if="$auth.isAuthenticated"
         >
            <img :src="this.$auth.user.picture" />
         </v-avatar>
      </v-app-bar>
      <v-btn
         id="control"
         v-if="this.userLogedIn"
         @click="addWindow = !addWindow"
         x-large
         rounded
         dark
         class="green darken-3"
      >
         <i class="large material-icons" style="font-size: 50px">add</i>
      </v-btn>
      <v-main class="grey lighten-3 px-10" v-if="this.userLogedIn">
         <v-row justify="center" align="center">
            <v-flex xs9>
               <v-icon
                  @click="refresh()"
                  large
                  style="margin-left: calc(50% - 30px); margin-bottom: 15px"
                  >refresh</v-icon
               >

               <div
                  v-for="task in tasks"
                  :key="task.id"
                  style="margin-bottom: 15px"
               >
                  <v-card>
                     <v-card-title primary-title>
                        <div>
                           <div>{{ task.description }}</div>
                        </div>
                     </v-card-title>

                     <v-card-actions>
                        <v-btn
                           @click="deleteTask(task.id)"
                           text
                           dark
                           color="green"
                           >Complete</v-btn
                        >
                        <v-btn
                           @click="deleteTask(task.id)"
                           text
                           dark
                           color="red"
                           >Delete</v-btn
                        >
                     </v-card-actions>
                  </v-card>
               </div>
            </v-flex>
         </v-row>
      </v-main>

      <v-main class="grey lighten-3 px-10" v-if="!this.userLogedIn">
         <v-row justify="center" align="center">
            <v-flex xs9>
               <v-content align="center">
                  <h1>Please Log-In To See Your Projects</h1>
                  <v-btn
                     dark
                     class="mt-7"
                     x-large
                     rounded
                     color="green"
                     @click="login"
                     >Log-In</v-btn
                  >
               </v-content>
            </v-flex>
         </v-row>
      </v-main>

      <AddTask
         @closed="
            addWindow = false;
            refresh();
         "
         v-if="addWindow"
         :project="openTab"
      />
   </v-app>
</template>

<script>
import AddTask from './AddTask.vue';
const phpURL = 'https://localhost/BugTracker/index.php';

export default {
   components: {
      AddTask,
   },
   data: () => ({
      userLogedIn: false,
      links: [],
      tasks: [],
      openTab: '',
      addWindow: false,
   }),
   beforeUpdate: function() {
      this.userLogedIn = this.$auth.user != undefined;
   },
   mounted: async function() {
      //Wait for user to be confirmed
      await setTimeout(() => {
         if (this.userLogedIn) {
            this.refresh();
         }
      }, 400);
   },
   methods: {
      //Refresh
      async refresh() {
         await this.getProjects();

         if (!this.links.includes(this.openTab)) {
            this.openTab = this.links[0];
         }

         this.getTasks();
      },
      // Log the user in
      login() {
         this.$auth.loginWithRedirect();
      },
      // Log the user out
      logout() {
         this.$auth.logout({
            returnTo: window.location.origin,
         });
      },
      updateTab() {
         const index = Math.max(this.links.length - 1, 0);
         this.openTab = this.links[index];
         this.getTasks();
      },
      changeTab(tab) {
         this.openTab = tab;
         this.getTasks();
      },
      async getProjects() {
         const axios = require('axios');
         let data = null;

         //Get Projects
         const params =
            '?confirm=confirm&type=getProjects&email=' + this.$auth.user.email;
         await axios
            .get(phpURL + params)
            .then(function(response) {
               data = response.data;
            })
            .catch(function(error) {
               // handle error
               console.log(error);
            });

         //Format into links
         this.links = [];
         data.forEach((element) => {
            let temp = JSON.parse(element);
            this.links.push(temp.project);
         });
      },
      async getTasks() {
         const axios = require('axios');
         let data = null;

         //Get Tasks
         const params =
            '?confirm=confirm&type=getTasks&email=' +
            this.$auth.user.email +
            '&project=' +
            this.openTab;
         await axios
            .get(phpURL + params)
            .then(function(response) {
               data = response.data;
            })
            .catch(function(error) {
               // handle error
               console.log(error);
            });

         //Format as a task obj
         this.tasks = [];
         data.forEach((task) => {
            task = JSON.parse(task);
            let obj = {
               id: task.task_id,
               description: task.description,
            };
            this.tasks.push(obj);
         });
      },
      async deleteTask(id) {
         const axios = require('axios');

         const length = this.tasks.length;

         //Delete Task
         const params = '?confirm=confirm&type=deleteTask&id=' + id;
         console.log(params);
         await axios.get(phpURL + params).catch(function(error) {
            // handle error
            console.log(error);
         });

         //If a project has been emptied
         if (this.tasks.length <= 1) {
            this.openTab = this.links[Math.max(this.links.length - 1, 0)];
         }

         //Refresh
         if (length <= 1) {
            await this.getProjects();
            this.updateTab();
         } else {
            this.getTasks();
         }
      },
   },
};
</script>

<style scoped>
#control {
   margin-left: calc(100vw - 115px);
   margin-top: calc(100vh - 120px);
   position: fixed;
   z-index: 1;
}
</style>
