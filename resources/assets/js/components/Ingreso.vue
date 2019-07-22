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
                        <i class="fa fa-align-justify"></i> Ingresos
                        <button type="button" @click="showDetail()" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!--Listado-->
                    <template v-if="listado">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="type_voucher">Tipo Comprobante</option>
                                      <option value="num_voucher">Numero Comprobante</option>
                                      <option value="date_hour">fecha-hora</option>
                                    </select>
                                     <input type="text" v-model="buscar" @keyup.enter="listIngreso(1,buscar,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listIngreso(1,buscar,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Proveedor</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Numero Comprobante</th>
                                        <th>Fecha Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="ingreso in arrayIngreso" :key="ingreso.id">
                                        <td>
                                            <button type="button" @click="openModal('ingreso','update', ingreso)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="ingreso.state=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="deactivateIngreso(ingreso.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                                
                                        </td>
                                        <td v-text="ingreso.user"></td>
                                        <td v-text="ingreso.name"></td>
                                        <td v-text="ingreso.type_voucher"></td>
                                        <td v-text="ingreso.serie_voucher"></td>
                                        <td v-text="ingreso.num_voucher"></td>
                                        <td v-text="ingreso.date_hour"></td>
                                        <td v-text="ingreso.total"></td>
                                        <td v-text="ingreso.tax"></td>
                                        <td v-text="ingreso.state"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                    </template>
                    <!--Fin Listado-->
                    <!--Detalle-->
                    <template v-else>
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Proveedor(*)</label>
                                    <v-select
                                        :on-search="selectSupplier"
                                        label="name"
                                        :options="arraySupplier"
                                        placeholder="Buscar Proveedores..."
                                        :onChange="getDataSupplier"                                        
                                    >
                                    <template slot="no-options">                                    Busqueda por nombre o nit..
                                    </template>

                                    </v-select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <label for="">Impuesto(*)</label>
                                    <input type="text" class="form-control" v-model="tax">
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante(*)</label>
                                    <select class="form-control" v-model="type_voucher">
                                        <option value="0">Seleccione</option>
                                        <option value="BOLETA">BOLETA</option>
                                        <option value="FACTURA">FACTURA</option>
                                        <option value="TICKET">TICKET</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <input type="text" class="form-control" v-model="serie_voucher" placeholder="000x">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante(*)</label>
                                    <input type="text" class="form-control" v-model="num_voucher" placeholder="000xx">
                                </div>
                            </div>

                        </div>
                        <div class="form-group row border">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Artículo</label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                                        <button class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="article">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="price">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" value="0" class="form-control" v-model="quantity">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Opciones</th>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <!--Eliminar el detalle del ingreso-->
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                <!--Nombre articulo-->
                                                Artículo n
                                            </td>
                                            <td>
                                                <!--input para el ingreso-->
                                                <input type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <!--Valor del ingreso-->
                                                <input type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                <!--Subtotal-->
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td>
                                                Artículo n
                                            </td>
                                            <td>
                                                <input type="number" value="3" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" value="2" class="form-control">
                                            </td>
                                            <td>
                                                $ 6.00
                                            </td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td>$ 5</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td>$ 1</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td>$ 6</td>
                                        </tr>
                                    </tbody>                                    
                                </table>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarIngreso()">Registrar Compra</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!--Fin Detalle-->
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
    import vSelect from 'vue-select';
    export default {
        data (){
           return {
               ingreso_id : 0,
               idsupplier :'',
               type_voucher : 'BOLETA',
               serie_voucher : '',
               num_voucher : '',
               tax : 0.18,
               total : 0.0,
               arrayIngreso : [],
               arraySupplier : [],
               arrayDetalle : [],
               listado: 1,
               modal: 0,
               tittlemodal : '',
               typeAction : 0,
               errorIngreso : 0,
               errorShowMsjIngreso : [],
               pagination : {
                   'total' : 0,
                   'current_page' : 0,
                   'per_page' : 0,
                   'last_page' : 0,
                   'from' : 0,
                   'to' : 0,
               },
               offset : 3,
               criterion : 'num_voucher',
               buscar : '',
               arrayArticle : [],
               idarticle : 0,
               code : '',
               article :  '',
               price : 0,
               quantity: 0
           } 
        },
        components:{
            vSelect
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
             listIngreso (page,buscar,criterion){
                let me=this;
                var url= '/ingreso?page=' + page + '&buscar='+ buscar + '&criterion='+ criterion;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayIngreso = respuesta.ingresos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectSupplier(search,loading){
                let me=this;
                loading(true)

                var url= '/supplier/selectSupplier?filter='+search;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    q: search
                    me.arraySupplier = respuesta.suppliers;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDataSupplier(val1){
                let me = this;
                me.loading = true;
                me.idsupplier = val1.id;
            },
            buscarArticulo(){
                var url= '/article/buscarArticulo?filter=' + this.code;

                axios.get(url).then(response => {
                    this.arrayArticle = response.data.articles;

                    if (this.arrayArticle.length > 0 ){
                        this.article = this.arrayArticle[0]['name'];
                        this.idarticle = this.arrayArticle[0]['id'];
                    }

                    else{
                        this.article = 'No existe artículo';
                        this.idarticle = 0;
                    }
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
                me.listIngreso(page, buscar, criterion);
            },
            registerPerson(){
                if (this.validePerson()){
                    return;
                }
                let me = this;

                axios.post('/user/register',{
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'user': this.user,
                    'password': this.password,
                    'idrol': this.idrol
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

                axios.put('/user/update',{
                    'name': this.name,
                    'type_document': this.type_document,
                    'num_document': this.num_document,
                    'address': this.address,
                    'phone': this.phone,
                    'email': this.email,
                    'user': this.user,
                    'password': this.password,
                    'idrol' : this.idrol,
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
                if (!this.user) this.errorShowMsjPerson.push("El nombre de usuario no puede estar vacío.");
                if (!this.password) this.errorShowMsjPerson.push("El password no puede estar vacío.");
                if (!this.idrol) this.errorShowMsjPerson.push("Debes seleccionar un rol");
                if (this.errorShowMsjPerson.length) this.errorPerson = 1;

                return this.errorPerson;
            },
            showDetail(){
                this.listado=0;
            },
            hideDetail(){
                this.listado=1;
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
                this.user='';
                this.password='';
                this.idrol=0;
                this.errorPerson=0;

            },
            openModal(model, action, data = []){
              this.selectRol();
              switch(model){
                  case "person":
                      {
                          switch(action){
                              case 'register':{
                                  this.modal = 1;
                                  this.tittlemodal = 'Registrar Usuario';
                                  this.name='';
                                  this.type_document='DNI';
                                  this.num_document='';
                                  this.address='';
                                  this.phone='';
                                  this.email='';
                                  this.user='';
                                  this.password='';
                                  this.idrol=0;
                                  this.typeAction = 1;
                                  break;
                              }
                              case 'update':{
                                  this.modal =1;
                                  this.tittlemodal = 'Actualizar usuario';
                                  this.typeAction = 2;
                                  this.persona_id = data['id'];
                                  this.name = data['name'];
                                  this.type_document = data['type_document'];
                                  this.num_document = data['num_document'];
                                  this.address = data['address'];
                                  this.phone = data['phone'];
                                  this.email = data['email'];
                                  this.user = data['user'];
                                  this.password = data['password'];
                                  this.idrol = data['idrol'];
                                  break;
                                 // console.log(data);
                              }
                          }
                      }
              }
            },
            deactivateUser(id){
                 swal({
                title: 'Esta seguro de desactivar esta Usuario?',
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

                    axios.put('/user/deactivate',{
                        'id': id
                    }).then(function (response) {
                        me.listPerson(1,'','name');
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
            activateUser(id){
                 swal({
                title: 'Esta seguro de activar este Usuario?',
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

                    axios.put('/user/activate',{
                        'id': id
                    }).then(function (response) {
                        me.listPerson(1,'','name');
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
        },
        mounted() {
            this.listIngreso(1, this.buscar, this.criterion);
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
    @media (min-witdh: 600px){
        .btnagregar {
            margin-top: 2rem;
        }
    }
</style>


