<template>
    <!-- Contenido Principal -->
<main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Clientes
                        <button type="button" @click="openModal('person','register')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="name">Nombre</option>
                                      <option value="num_document">Documento</option>
                                      <option value="email">Email</option>
                                      <option value="phone">Telefono</option>
                                    </select>
                                     <input type="text" v-model="buscar" @keyup.enter="listPerson(1,buscar,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listPerson(1,buscar,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Tipo Documento</th>
                                    <th>Numero</th>
                                    <th>Direccion</th>
                                    <th>Telefono</th>
                                    <th>email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="person in arrayPerson" :key="person.id">
                                    <td>
                                        <button type="button" @click="openModal('person','update', person)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> 
                                    </td>
                                    <td v-text="person.name"></td> 
                                    <td v-text="person.type_document"></td>
                                    <td v-text="person.num_document"></td>
                                    <td v-text="person.address"></td>
                                    <td v-text="person.phone"></td>
                                    <td v-text="person.email"></td>
                                   
                                </tr>
                                
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,buscar,criterion)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="changePage(page,buscar,criterion)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page + 1,buscar,criterion)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade"  tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tittlemodal"></h4>
                            <button type="button" class="close" @click="closeModal()"  aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de la persona">
                                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de Documento</label>
                                    <div class="col-md-9">
                                        <select v-model="type_document" class="form-control">
                                            <option value="DNI">DNI</option>
                                            <option value="INE">INE</option>
                                            <option value="PASS">PASS</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Numero</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="num_document" class="form-control" placeholder="Numero de Documento">
                                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Direccion</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="address" class="form-control" placeholder="Direccion">
                                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Telefono</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="phone" class="form-control" placeholder="Telefono">
                                
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">email</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" class="form-control" placeholder="Email">
                                
                                    </div>
                                </div>

                                <div v-show="errorPerson" class="form-group row">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjPerson" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                                    
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerPerson()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updatePerson()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
            
         </main>
        <!-- /Fin del contenido principal -->

</template>

<script>
    export default {
        data (){
           return {
               persona_id : 0,
               name :'',
               type_document : 'INE',
               num_document : '',
               address : '',
               phone : '',
               email : '',
               arrayPerson : [],
               modal: 0,
               tittlemodal : '',
               typeAction : 0,
               errorPerson : 0,
               errorShowMsjPerson : [],
               pagination : {
                   'total' : 0,
                   'current_page' : 0,
                   'per_page' : 0,
                   'last_page' : 0,
                   'from' : 0,
                   'to' : 0,
               },
               offset : 3,
               criterion : 'name',
               buscar : ''
           } 
        },
        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  

                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods : {
             listPerson (page,buscar,criterion){
                let me=this;
                var url= '/client?page=' + page + '&buscar='+ buscar + '&criterion='+ criterion;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPerson = respuesta.persons.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            changePage (page, buscar, criterion){
                let me = this;
                //Actualizar pagina actual
                me.pagination.current_page = page;
                //Envia la petición para vizualizar la data desde esa pagina 
                me.listPerson(page, buscar, criterion);
            },
            registerPerson(){
                if (this.validePerson()){
                    return;
                }
                let me = this;

                axios.post('/client/register',{
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email
                }).then(function (response) {
                    me.closeModal();
                    me.listPerson(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            updatePerson(){
                if (this.validePerson()){
                    return;
                }
                let me = this;

                axios.put('/client/update',{
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'id': this.persona_id
                }).then(function (response) {
                    me.closeModal();
                    me.listPerson(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validePerson(){
                this.errorPerson=0;
                this.errorShowMsjPerson =[];

                if (!this.name) this.errorShowMsjPerson.push("El nombre de la persona no puede estar vacío.");
                if (this.errorShowMsjPerson.length) this.errorPerson = 1;

                return this.errorPerson;
            },
            closeModal(){
                this.modal =0;
                this.tittlemodal='';
                this.name='';
                this.type_document='DNI';
                this.num_document='';
                this.address='';
                this.phone='';
                this.email='';
                this.errorPerson=0;

            },
            openModal(model, action, data = []){
              switch(model){
                  case "person":
                      {
                          switch(action){
                              case 'register':{
                                  this.modal = 1;
                                  this.tittlemodal = 'Registrar Cliente';
                                  this.name='';
                                  this.type_document='DNI';
                                  this.num_document='';
                                  this.address='';
                                  this.phone='';
                                  this.email='';
                                  this.typeAction = 1;
                                  break;
                              }
                              case 'update':{
                                  this.modal =1;
                                  this.tittlemodal = 'Actualizar Cliente';
                                  this.typeAction = 2;
                                  this.persona_id = data['id'];
                                  this.name = data['name'];
                                  this.type_document = data['type_document'];
                                  this.num_document = data['num_document'];
                                  this.address = data['address'];
                                  this.phone = data['phone'];
                                  this.email = data['email'];
                                  break;
                                 // console.log(data);
                              }
                          }
                      }
              }
            }
        },
        mounted() {
            this.listPerson(1, this.buscar, this.criterion);
            //console.log('Component mounted.')

        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;

    }
    .show{
      display: list-item !important;
      opacity: 1 !important;
      position: absolute !important;
      background-color: #3c29297a !important;

    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>


