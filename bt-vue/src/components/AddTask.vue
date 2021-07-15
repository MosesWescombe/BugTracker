<template>
   <div class="text-xs-center">
      <v-dialog v-model="open" width="500">
         <v-card>
            <v-card-title class="headline grey lighten-2" primary-title>
               Add Task
            </v-card-title>

            <form class="add-form">
               <!-- Importance -->
               <div class="form-control">
                  <label>Importance</label>
                  <select v-model="level" name="level">
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                  </select>
               </div>
               <!-- Project folder -->
               <div class="form-control">
                  <label>Project</label>
                  <input type="text" v-model="projectInput" name="project" />
               </div>
               <!-- Add Users -->
               <div class="form-control">
                  <label left>Add Users - (By Email)</label>

                  <input
                     style="width: 89%"
                     type="text"
                     v-model="usersInput"
                     name="usersInput"
                  />
                  <v-btn @click="addUser()" icon color="green" large
                     ><i class="large material-icons" style="font-size: 50px"
                        >add</i
                     ></v-btn
                  >
                  <div style="display: flex; flex-wrap: wrap;">
                     <v-chip
                        class="ml-1 mb-1"
                        @click="removeUser(index)"
                        v-for="(user, index) in users"
                        :key="index"
                        :v-model="users[index]"
                        >{{ user }}</v-chip
                     >
                  </div>
               </div>
               <!-- Description -->
               <div class="form-control">
                  <label>Description</label>
                  <textarea
                     type="text"
                     v-model="description"
                     name="description"
                  />
               </div>
            </form>

            <v-divider></v-divider>

            <v-card-actions>
               <v-spacer></v-spacer>
               <v-btn color="primary" text @click="closeWindow()">
                  Close
               </v-btn>
               <v-btn color="primary" text @click="submitWindow()">
                  Submit
               </v-btn>
            </v-card-actions>
         </v-card>
      </v-dialog>
   </div>
</template>

<script>
export default {
   data: () => ({
      phpURL: 'https://localhost/BugTracker/index.php',
      open: true,
      description: '',
      projectInput: 'Test',
      usersInput: '',
      users: [],
      level: 1,
   }),
   props: {
      project: String,
   },
   mounted: function() {
      this.projectInput = this.project;
      this.users.push(this.$auth.user.email);
   },
   methods: {
      removeUser(index) {
         if (index != this.users.indexOf(this.$auth.user.email)) {
            this.users = this.users.filter(function(value, i) {
               return i != index;
            });
         }
      },
      async addUser() {
         if (this.usersInput != '' && !this.users.includes(this.usersInput)) {
            //Check that the user exists
            const axios = require('axios');
            const params =
               '?confirm=confirm&type=getUserID&email=' + this.usersInput;
            let data = '';

            await axios
               .get(this.phpURL + params)
               .then(function(res) {
                  if (res.data == '') {
                     data = -1;
                  } else {
                     data = parseInt(JSON.parse(res.data).user_id);
                  }
               })
               .catch(function(error) {
                  // handle error
                  console.log(error);
               });

            if (data != -1) {
               this.users.push(this.usersInput);
            } else {
               alert(
                  `Ther is no registered user with the email: '${this.usersInput}'`
               );
            }
         }
      },
      closeWindow() {
         if (this.description != '') {
            if (
               confirm(
                  'You have unsubmitted data, are you sure you want to close?'
               )
            ) {
               this.$emit('closed');
            }
         } else {
            this.$emit('closed');
         }
      },
      async submitWindow() {
         if (this.description == '') {
            alert('You must add a description');
         } else {
            await this.addTask();
            this.$emit('closed');
         }
      },
      async addTask() {
         const axios = require('axios');

         //Add Task
         let task_id = 'Not set';
         let params =
            '?level=' +
            this.level +
            '&description=' +
            this.description +
            '&project=' +
            this.projectInput +
            '&confirm=confirm&type=addTask';

         await axios
            .get(this.phpURL + params)
            .then(function(response) {
               task_id = parseInt(response.data.task_id);
            })
            .catch(function(error) {
               // handle error
               console.log(error);
            });

         //Add Links
         await this.users.forEach(async (email) => {
            params =
               '?confirm=confirm&type=addLink&email=' +
               email +
               '&id=' +
               task_id;
            await axios.get(this.phpURL + params).catch(function(error) {
               // handle error
               console.log(error);
            });
         });
      },
   },
};
</script>

<style scoped>
.add-form {
   margin-bottom: 40px;
   padding: 10px;
}
.form-control {
   margin: 20px 0;
}
.form-control label {
   display: block;
}
.form-control input {
   width: 100%;
   height: 40px;
   margin: 5px;
   margin-left: 0px;
   padding: 3px 7px;
   font-size: 17px;
   border-radius: 5px;
   border: 1px solid grey;
}
.form-control textarea {
   width: 100%;
   height: 120px;
   margin: 5px;
   margin-left: 0px;
   padding: 3px 7px;
   font-size: 17px;
   border-radius: 5px;
   border: 1px solid grey;
}
.form-control select {
   width: 100%;
   height: 40px;
   margin: 5px;
   margin-left: 0px;
   padding: 3px 7px;
   font-size: 17px;
   border-radius: 5px;
   border: 1px solid grey;
}
.form-control-check {
   display: flex;
   align-items: center;
   justify-content: space-between;
}
.form-control-check label {
   flex: 1;
}
.form-control-check input {
   flex: 2;
   height: 20px;
}
</style>
