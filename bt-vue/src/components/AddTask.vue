<template>
   <div class="text-xs-center">
      <v-dialog v-model="open" width="500">
         <v-card>
            <v-card-title class="headline grey lighten-2" primary-title>
               Add Task - {{ project }}
            </v-card-title>

            <form class="add-form">
               <div class="form-control">
                  <label>Importance</label>
                  <select id="country" v-model="level" name="level">
                     <option value="1">1</option>
                     <option value="2">2</option>
                     <option value="3">3</option>
                     <option value="4">4</option>
                  </select>
               </div>
               <div class="form-control">
                  <label>Project</label>
                  <input
                     type="text"
                     v-model="projectInput"
                     name="projectInput"
                  />
               </div>
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
      open: true,
      description: '',
      projectInput: 'Test',
      level: 1,
   }),
   props: {
      project: String,
   },
   mounted: function() {
      this.projectInput = this.project;
   },
   methods: {
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

         //Get Projects
         const params =
            '?level=' +
            this.level +
            '&description=' +
            this.description +
            '&project=' +
            this.projectInput +
            '&confirm=confirm';

         await axios
            .get(
               'http://addtask-env-1.eba-ypi3dtjm.ap-southeast-2.elasticbeanstalk.com/' +
                  params
            )
            .then(function(response) {
               console.log(response);
            })
            .catch(function(error) {
               // handle error
               console.log(error);
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
