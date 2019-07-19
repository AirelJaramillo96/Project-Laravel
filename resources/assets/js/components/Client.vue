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
                                        </button> &nbsp;
                                    </td>
                                    <td v-text="person.name"></td>
                                    <td v-text="person.type_document"></td>
                                    <td v-text="person.num_document"></td>
                                    <td v-text="person.address"></td>
                                    <td v-text="person.phone"></td>
                                    <td v-text="person.email"></td>
                                    <td> 
                                        <div v-if="category.condition">
                                            <span class="badge badge-success">Activo</span>
                                        </div>

                                        <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                        </div>
                                    </td>
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
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de categoría">
                                        <!--<span class="help-block">(*) Ingrese el nombre de la categoría</span>-->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese Descripcion">
                                    </div>
                                </div>
                                <div v-show="errorCategory" class="form-group row">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjCategory" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                                    
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerCategory()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateCategory()">Actualizar</button>
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
               errorPersona : 0,
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
            listPerson (page, buscar, criterion){
                let me=this;
                var url = '/client?page=' + page + '&buscar='+ buscar + '&criterion='+ criterion;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayPerson = respuesta.persons.data;
                    me.pagination= respuesta.pagination;
                // handle success
                console.log(response);
                })
                .catch(function (error) {
                // handle error
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
            registerCategory(){
                if (this.valideCategory()){
                    return;
                }
                let me = this;

                axios.post('/category/register',{
                    'name': this.name,
                    'description': this.description
                }).then(function (response) {
                    me.closeModal();
                    me.listCategory(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            updateCategory(){
                if (this.valideCategory()){
                    return;
                }
                let me = this;

                axios.put('/category/update',{
                    'name': this.name,
                    'description': this.description,
                    'id': this.category_id
                }).then(function (response) {
                    me.closeModal();
                    me.listCategory(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                });
            },
            valideCategory(){
                this.errorCategory=0;
                this.errorShowMsjCategory =[];

                if (!this.name) this.errorShowMsjCategory.push("El nombre de la categoría no puede estar vacío.");
                if (this.errorShowMsjCategory.length) this.errorCategory = 1;

                return this.errorCategory;
            },
            closeModal(){
                this.modal =0;
                this.tittlemodal='';
                this.name='';
                this.description="";

            },
            openModal(model, action, data = []){
              switch(model){
                  case "category":
                      {
                          switch(action){
                              case 'register':{
                                  this.modal = 1;
                                  this.tittlemodal = 'Register Category';
                                  this.name = '';
                                  this.description = '';
                                  this.typeAction = 1;
                                  break;
                              }
                              case 'update':{
                                  this.modal =1;
                                  this.tittlemodal = 'Update Category';
                                  this.typeAction = 2;
                                  this.name = data['name'];
                                  this.description = data['description'];
                                  this.category_id = data['id'];
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


