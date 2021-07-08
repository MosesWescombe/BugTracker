<template>
   <v-app id="inspire">
      <v-btn
         id="control"
         @click="addWindow = !addWindow"
         x-large
         rounded
         dark
         class="green darken-3"
      >
         <i class="large material-icons" style="font-size: 50px">add</i>
      </v-btn>

      <v-app-bar app color="white" flat>
         <v-avatar
            :color="$vuetify.breakpoint.smAndDown ? 'black' : 'transparent'"
            size="32"
         ></v-avatar>

         <v-tabs centered class="ml-n9" color="black">
            <v-tab @click="changeTab(link)" v-for="link in links" :key="link">
               {{ link }}
            </v-tab>
         </v-tabs>

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
      </v-app-bar>

      <v-main class="grey lighten-3 px-10">
         <v-row justify="center" align="center">
            <v-flex xs9>
               <v-icon
                  @click="
                     tasks = [];
                     getTasks();
                  "
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
                        <v-btn text dark color="green">Complete</v-btn>
                        <v-btn
                           @click="deleteTask(task.id)"
                           text
                           dark
                           color="red"
                           >Delete</v-btn
                        >
                     </v-card-actions>
                  </v-card>
                  <v-container dark class="grey"></v-container>
               </div>
            </v-flex>
         </v-row>
      </v-main>
      <AddTask
         @closed="
            addWindow = false;
            getProjects();
            getTasks();
         "
         v-if="addWindow"
         :project="openTab"
      />
   </v-app>
</template>

<script>
import AddTask from './AddTask.vue';

export default {
   components: {
      AddTask,
   },
   data: () => ({
      links: [],
      tasks: [],
      openTab: '',
      addWindow: false,
   }),
   mounted: async function() {
      await this.getProjects();
      this.openTab = this.links[0];
      this.getTasks();
   },
   methods: {
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
         await axios
            .get('http://localhost/BugTracker/getProjects.php')
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
         await axios
            .get(
               'http://localhost/BugTracker/getTasks.php' +
                  '?project=' +
                  this.openTab
            )
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

         //Delete Task
         const params = '?id=' + id;
         await axios
            .get('http://localhost/BugTracker/deleteTask.php' + params)
            .then(function() {})
            .catch(function(error) {
               // handle error
               console.log(error);
            });

         //If a project has been emptied
         if (this.tasks.length <= 1) {
            this.openTab = this.links[Math.max(this.links.length - 1, 0)];
         }

         //Refresh
         await this.getProjects();
         this.getTasks();
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
