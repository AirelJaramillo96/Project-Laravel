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
                        <i class="fa fa-align-justify"></i> Articulos
                        <button type="button" @click="openModal('article','register')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="name">Nombre</option>
                                      <option value="description">Descripción</option>
                                    </select>
                                     <input type="text" v-model="buscar" @keyup.enter="listArticle(1,buscar,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listArticle(1,buscar,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Categoria</th>
                                    <th>Precio Venta</th>
                                    <th>Stock</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="article in arrayArticle" :key="article.id">
                                    <td>
                                        <button type="button" @click="openModal('article','update', article)" class="btn btn-warning btn-sm">
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                            <template v-if="article.condition">
                                                <button type="button" class="btn btn-danger btn-sn" @click="deactivateCategory(article.id)">
                                                   <i class="icon-trash"></i>
                                                </button> 
                                            </template>
                                            <template v-else>
                                                <button type="button" class="btn btn-info btn-sn" @click="activateCategory(article.id)">
                                                   <i class="icon-check"></i>
                                                </button> 
                                            </template>
                                       <!-- <button type="button" class="btn btn-danger btn-sm">
                                          <i class="icon-trash"></i>
                                        </button>-->
                                    </td>
                                    <td v-text="article.code"></td>
                                    <td v-text="article.name"></td>
                                    <td v-text="article.name_category"></td>
                                    <td v-text="article.price_vent"></td>
                                    <td v-text="article.stock"></td>
                                    <td v-text="article.description"></td>
                                    <td>
                                        <div v-if="article.condition">
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
                                    <label class="col-md-3 form-control-label" for="text-input">Categoria</label>
                                    <div class="col-md-9">
                                        
                                        <select class="form-control" v-model="idcategory">
                                            <option value="0" disabled>Seleccione</option>
                                            <option v-for="category in arrayCategory" :key="category.id" :value="category.id" v-text="category.name"></option>
                                        </select>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Código</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="code" class="form-control" placeholder="Código de Barras">
                                        <!--<span class="help-block">(*) Ingrese el nombre de la categoría</span>-->
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="name" class="form-control" placeholder="Nombre de artículo">
                                        <!--<span class="help-block">(*) Ingrese el nombre de la categoría</span>-->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Precio Venta</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="price_vent" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Stock</label>
                                    <div class="col-md-9">
                                        <input type="number" v-model="stock" class="form-control" placeholder="">                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripción</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="description" class="form-control" placeholder="Ingrese Descripcion">
                                    </div>
                                </div>
                                <div v-show="errorArticle" class="form-group row">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjArticle" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                                    
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                            <button type="button" v-if="typeAction==1" class="btn btn-primary" @click="registerArticle()">Guardar</button>
                            <button type="button" v-if="typeAction==2" class="btn btn-primary" @click="updateArticle()">Actualizar</button>
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
               article_id : 0,
               idcategory: 0,
               name_category :'',
               code : '',
               name : '',
               price_vent: 0,
               stock : 0,
               description : '',
               arrayArticle : [],
               modal: 0,
               tittlemodal : '',
               typeAction : 0,
               errorArticle : 0,
               errorShowMsjArticle : [],
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
               buscar : '',
               arrayCategory : []
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
            listArticle (page, buscar, criterion){
                let me=this;
                var url = '/article?page=' + page + '&buscar='+ buscar + '&criterion='+ criterion;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticle = respuesta.articles.data;
                    me.pagination= respuesta.pagination;
                // handle success
                console.log(response);
                })
                .catch(function (error) {
                // handle error
                 console.log(error);
                });
            },
            selectCategory(){
                let me=this;
                var url = '/category/selectCategory?page';
                axios.get(url).then(function (response) {
                   // console.log(response);
                    var respuesta= response.data;
                    me.arrayCategory = respuesta.categories;
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
                me.listArticle(page, buscar, criterion);
            },
            registerArticle(){
                if (this.valideArticle()){
                    return;
                }
                let me = this;

                axios.post('/article/register',{
                    'idcategory': this.idcategory,
                    'code': this.code,
                    'name': this.name,
                    'stock': this.stock,
                    'price_vent': this.price_vent,
                    'description': this.description
                }).then(function (response) {
                    me.closeModal();
                    me.listArticle(1,'','name');
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
            deactivateCategory(id){
                 swal({
                title: 'Esta seguro de desactivar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/category/deactivate',{
                        'id': id
                    }).then(function (response) {
                        me.listCategory(1,'','name');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                })
            },
            activateCategory(id){
                 swal({
                title: 'Esta seguro de activar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/category/activate',{
                        'id': id
                    }).then(function (response) {
                        me.listCategory(1,'','name');
                        swal(
                        'Activado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                })
            },
            valideArticle(){
                this.errorArticle=0;
                this.errorShowMsjArticle =[];

                if (this.idcategory==0) this.errorShowMsjArticle.push("Seleccione una categoría.");
                if (!this.name) this.errorShowMsjArticle.push("El nombre del artículo no puede estar vacío.");
                if (!this.stock) this.errorShowMsjArticle.push("El stock del artículo debe ser un número y no puede estar vacío.");
                if (!this.price_vent) this.errorShowMsjArticle.push("El precio venta del artículo debe ser un número y no puede estar vacío.");

                if (this.errorShowMsjArticle.length) this.errorArticle = 1;

                return this.errorArticle;
            },
            closeModal(){
                this.modal =0;
                this.tittlemodal='';
                this.idcategory=0;
                this.name_category = '';
                this.code = '';
                this.name = '';
                this.price_vent = 0;
                this.stock = 0;
                this.description = '';
                this.errorArticle=0;

            },
            openModal(model, action, data = []){
              switch(model){
                  case "article":
                      {
                          switch(action){
                              case 'register':{
                                  this.modal = 1;
                                  this.tittlemodal = 'Registrar Articulo';
                                  this.idcategory = 0;
                                  this.name_category = '';
                                  this.code = '';
                                  this.name = '';
                                  this.price_vent = 0;
                                  this.stock = 0;
                                  this.description = '';
                                  this.typeAction = 1;
                                  break;
                              }
                              case 'update':{
                                  this.modal =1;
                                  this.tittlemodal = 'Actualizar Articulo';
                                  this.typeAction = 2;
                                  this.article_id = data['id'];
                                  this.idcategory = data['idcategory'];
                                  this.code = data['code'];
                                  this.name = data['name'];
                                  this.stock = data['stock'];
                                  this.price_vent=data['price_vent'];
                                  this.description = data['description'];
                                  break;
                                 // console.log(data);
                              }
                          }
                      }
              }
              this.selectCategory();
            }
        },
        mounted() {
            this.listArticle(1, this.buscar, this.criterion);
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


