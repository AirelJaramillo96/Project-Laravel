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
                        <i class="fa fa-align-justify"></i> Ventas
                        <button type="button" @click="showDetail()" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <!--Listado-->
                    <template v-if="listado==1">
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterion">
                                      <option value="tipo_comprobante">Tipo Comprobante</option>
                                      <option value="num_comprobante">Numero Comprobante</option>
                                      <option value="fecha_hora">fecha-hora</option>
                                    </select>
                                     <input type="text" v-model="buscar" @keyup.enter="listVenta(1,buscar,criterion)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listVenta(1,buscar,criterion)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
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
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button> &nbsp;
                                            <template v-if="venta.estado=='Registrado'">
                                                <button type="button" class="btn btn-danger btn-sm" @click="deactivateVenta(venta.id)">
                                                    <i class="icon-trash"></i>
                                                </button>
                                            </template>
                                                
                                        </td>
                                        <td v-text="venta.user"></td>
                                        <td v-text="venta.name"></td>
                                        <td v-text="venta.type_voucher"></td>
                                        <td v-text="venta.serie_voucher"></td>
                                        <td v-text="venta.num_voucher"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.tax"></td>
                                        <td v-text="venta.estado"></td>
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
                    <template v-else-if="listado==0">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">Cliente(*)</label>
                                    <v-select
                                        :on-search="selectCliente"
                                        label="name"
                                        :options="arrayCliente"
                                        placeholder="Buscar Clientes..."
                                        :onChange="getDataCliente"                                        
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
                            <div class="col-md-12">
                                <div v-show="errorVenta" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorShowMsjVenta" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row border">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Artículo<span style="color:red;" v-show="idarticle==0">(*Seleccione)</span></label>
                                    <div class="form-inline">
                                        <input type="text" class="form-control" v-model="code" @keyup.enter="buscarArticulo()" placeholder="Ingrese artículo">
                                        <button @click="openModal()" class="btn btn-primary">...</button>
                                        <input type="text" readonly class="form-control" v-model="article">
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Precio<span style="color:red;" v-show="price==0">(*Ingrese precio)</span></label>
                                    <input type="number" value="0" step="any" class="form-control" v-model="price">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad<span style="color:red;" v-show="quantity==0">(*Ingrese Cantidad)</span></label>
                                    <input type="number" value="0" class="form-control" v-model="quantity">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Descuento>(*Ingrese Cantidad)</label>
                                    <input type="number" value="0" class="form-control" v-model="descuento">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button @click="agregarDetalle()" class="btn btn-success form-control btnagregar"><i class="icon-plus"></i></button>
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
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                            <td v-text="detalle.article">
                                                <!--Nombre articulo-->
                                               
                                            </td>
                                            <td>
                                                <!--input para el ingreso-->
                                                <input v-model="detalle.price" type="number"  class="form-control">
                                            </td>
                                            <td>
                                                <!--Valor del ingreso-->
                                                <span style="color:red;" v-show="detalle.quantity>detalle.stock">Stock:
                                                     {{detalle.stock}}</span>
                                                <input v-model="detalle.quantity" type="number"  class="form-control">
                                            </td>
                                            <td>
                                                <!--Valor del ingreso-->
                                                <span style="color:red;" v-show="detalle.descuento>(detalle.price*detalle.quantity)">Descuento superior</span>
                                                <input v-model="detalle.descuento" type="number"  class="form-control">
                                            </td>
                                            <td>
                                                <!--Subtotal-->

                                                {{detalle.price*detalle.quantity-detalle.descuento}}
                                            </td>
                                        </tr>
                                        
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td> {{totalPartial=(total-totalTax).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td> {{totalTax=((total*tax)/(1+tax)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td> {{total=calcularTotal}}</td>
                                        </tr>
                                    </tbody> 
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                   
                                </table>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                                <button type="button" class="btn btn-primary" @click="registrarVenta()">Registrar Venta</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!--Fin Detalle-->
                    <!--Ver Ingresos-->
                    <template v-else-if="listado==2">
                    <div class="card-body">
                        <div class="form-group row border">
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="">cliente</label>
                                    <p v-text="cliente"></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <label for="">Impuesto</label>
                                    <p v-text="tax"></p>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo Comprobante</label>
                                    <p v-text="type_voucher"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Serie Comprobante</label>
                                    <p v-text="serie_voucher"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Número Comprobante</label>
                                    <p v-text="num_voucher"></p>
                                </div>
                            </div>
                           

                        </div>
                        
                        <div class="form-group row">
                            <div class="table-responsive col-md-12">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Descuento</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle) in arrayDetalle" :key="detalle.id">
                                           
                                            <td v-text="detalle.articulo">
                                                <!--Nombre articulo-->
                                               
                                            </td>
                                            <td v-text="detalle.precio">
                                                <!--input para el ingreso-->
                                            </td>
                                            <td v-text="detalle.cantidad">  
                                                <!--Valor del ingreso-->
                                            </td>
                                            <td v-text="detalle.descuento">  
                                                <!--Valor del ingreso-->
                                            </td>
                                            <td>
                                                <!--Subtotal-->
                                                {{detalle.precio*detalle.cantidad-detalle.descuento}}
                                            </td>
                                        </tr>
                                        
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                            <td> {{totalPartial=(total-totalTax).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                            <td> {{totalTax=((total*tax)).toFixed(2)}}</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5;">
                                            <td colspan="4" align="right"><strong>Total Neto:</strong></td>
                                            <td> {{total}}</td>
                                        </tr>
                                    </tbody> 
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="5">
                                                No hay articulos agregados
                                            </td>
                                        </tr>
                                    </tbody>                                   
                                </table>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button type="button" @click="hideDetail()" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                    </template>
                    <!--Fin ver Ingresos-->
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
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterionA">
                                        <option value="name">Nombre</option>
                                        <option value="description">Descripción</option>
                                        <option value="description">Codigo</option>
                                        </select>
                                        <input type="text" v-model="buscarA" @keyup.enter="listArticle(buscarA,criterionA)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listArticle(buscarA,criterionA)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Opciones</th>
                                                <th>Codigo</th>
                                                <th>Nombre</th>
                                                <th>Categoria</th>
                                                <th>Precio Venta</th>
                                                <th>Stock</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="article in arrayArticle" :key="article.id">
                                                <td>
                                                    <button type="button" @click="agregarDetalleModal(article)" class="btn btn-success btn-sm">
                                                    <i class="icon-check"></i>
                                                    </button>
                                                <!-- <button type="button" class="btn btn-danger btn-sm">
                                                    <i class="icon-trash"></i>
                                                    </button>-->
                                                </td>
                                                <td v-text="article.code"></td>
                                                <td v-text="article.name"></td>
                                                <td v-text="article.name_category"></td>
                                                <td v-text="article.price_vent"></td>
                                                <td v-text="article.stock"></td>
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

                            </div>
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
               venta_id : 0,
               idcliente :'',
               cliente : '',
               type_voucher : 'BOLETA',
               serie_voucher : '',
               num_voucher : '',
               tax : 0.18,
               total : 0.0,
               totalTax : 0.0,
               totalPartial : 0.0,
               arrayVenta : [],
               arrayCliente : [],
               arrayDetalle : [],
               listado: 1,
               modal: 0,
               tittlemodal : '',
               typeAction : 0,
               errorVenta : 0,
               errorShowMsjVenta : [],
               pagination : {
                   'total' : 0,
                   'current_page' : 0,
                   'per_page' : 0,
                   'last_page' : 0,
                   'from' : 0,
                   'to' : 0,
               },
               offset : 3,
               criterion : 'num_comprobante',
               buscar : '',
               criterionA:'name',
               buscarA:'',
               arrayArticle : [],
               idarticle : 0,
               code : '',
               article :  '',

               price : 0,
               quantity: 0,
               descuento: 0,
               stock: 0
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
            },
            calcularTotal: function(){
                var resultado=0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado=resultado+(this.arrayDetalle[i].price*this.arrayDetalle[i].quantity-this.arrayDetalle[i].descuento)
                }
                return resultado;
            }
        },
        methods : {
             listVenta (page,buscar,criterion){
                let me=this;
                var url= '/venta?page=' + page + '&buscar='+ buscar + '&criterion='+ criterion;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayVenta = respuesta.ventas.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            selectCliente(search,loading){
                let me=this;
                loading(true)

                var url= '/client/selectCliente?filter='+search;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    q: search
                    me.arrayCliente = respuesta.clientes;
                    loading(false)
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getDataCliente(val1){
                let me = this;
                me.loading = true;
                me.idcliente = val1.id;
            },
            buscarArticulo(){
                let me=this;
                var url= '/article/buscarArticuloVenta?filtro=' + me.code;

                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticle = respuesta.articulos;

                    if (me.arrayArticle.length>0){
                        me.article=me.arrayArticle[0]['name'];
                        me.idarticle=me.arrayArticle[0]['id'];
                        me.price=me.arrayArticle[0]['price_vent'];
                        me.stock=me.arrayArticle[0]['stock'];
                    }
                    else{
                        me.article='No existe artículo';
                        me.idarticle=0;
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
                me.listVenta(page, buscar, criterion);
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].idarticle==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            agregarDetalle(){
                let me=this;
                if(me.idarticle==0 || me.quantity==0 || me.price==0){

                }
                else{
                    if(me.encuentra(me.idarticle)){
                         swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                            })
                    }else{
                        if(me.quantity>me.stock){
                            swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'No hay stock',
                            })

                        }else{
                            me.arrayDetalle.push({
                            idarticle: me.idarticle,
                            article: me.article,
                            quantity: me.quantity,
                            price: me.price,
                            descuento: me.descuento,
                            stock: me.stock
                            });
                            me.code="";
                            me.idarticle=0;
                            me.article="";
                            me.quantity=0;
                            me.price=0;
                            me.descuento=0;
                            me.stock

                        }

                    }
                
                }
                
            },
            agregarDetalleModal(data =[]){

               let me=this;
               if(me.encuentra(data['id'])){
                         swal({
                            type: 'error',
                            title: 'Error...',
                            text: 'Ese artículo ya se encuentra agregado!',
                            })
                    }else{
                        me.arrayDetalle.push({
                            idarticle: data['id'],
                            article: data['name'],
                            quantity: 1,
                            price: data['price_vent'],
                            descuento:0,
                            stock: data['stock']
                        });

                    }
            },
            listArticle (buscar, criterion){
                let me=this;
                var url = '/article/listarArticuloVenta?buscar='+ buscar + '&criterion='+ criterion;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayArticle = respuesta.articles.data;
                // handle success
                console.log(response);
                })
                .catch(function (error) {
                // handle error
                 console.log(error);
                });
            },
            registrarVenta(){
                if (this.valideVenta()){
                    return;
                }
                let me = this;

                axios.post('/venta/registrar',{
                    'idcliente': this.idcliente,
                    'tipo_comprobante': this.type_voucher,
                    'serie_comprobante' : this.serie_voucher,
                    'num_comprobante': this.num_voucher,
                    'impuesto': this.tax,
                    'total': this.total,
                    'data': this.arrayDetalle

                }).then(function (response) {
                   me.listado=1;
                   me.listVenta(1,'','num_voucher');
                   me.idcliente=0;
                    me.type_voucher ='BOLETA';
                    me.serie_voucher='';
                    me.num_voucher='';
                    me.tax=0.18;
                    me.total=0.0;
                    me.idarticle=0;
                    me.article='';
                    me.quantity=0;
                    me.price=0;
                    me.stock=0;
                    me.code='';
                    me.descuento=0;
                    me.arrayDetalle=[];
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
            valideVenta(){
                let me=this;

                me.errorVenta=0;
                me.errorShowMsjVenta =[];
                var art;

                me.arrayDetalle.map(function(x){
                    if(x.quantity>x.stock){
                        art=x.article + " con stock insuficiente";
                        me.errorShowMsjVenta.push(art);
                    }
                });

                if (me.idcliente==0) me.errorShowMsjVenta.push("Seleccione un Proveedor");
                if (me.type_voucher==0) me.errorShowMsjVenta.push("Seleccione el comprobante");
                if (!me.num_voucher) me.errorShowMsjVenta.push("Ingrese el número de comprobante");
                if (!me.tax) me.errorShowMsjVenta.push("Ingrese el impuesto de compra");
                if (me.arrayDetalle.length<=0) me.errorShowMsjVenta.push("Ingrese detalles");



                if (me.errorShowMsjVenta.length) me.errorVenta = 1;

                return me.errorVenta;
            },
            showDetail(){
                let me=this;
                me.listado=0;
                
                me.idproveedor=0;
                me.type_voucher ='BOLETA';
                me.serie_voucher='';
                me.num_voucher='';
                me.tax=0.18;
                me.total=0.0;
                me.idarticle=0;
                me.article='';
                me.quantity=0;
                me.price=0;
                me.arrayDetalle=[];
            },
            hideDetail(){
                this.listado=1;
            },
            verVenta(id){
                let me=this;
                this.listado =2;
                //Obtener los datos del ingreso
                var arrayVentaT=[];
                var url= '/venta/obtenerCabecera?id=' + id;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    arrayVentaT = respuesta.venta;
                    me.cliente = arrayVentaT[0]['name'];
                    me.type_voucher = arrayVentaT[0]['tipo_comprobante'];
                    me.serie_voucher = arrayVentaT[0]['serie_comprobante'];
                    me.num_voucher = arrayVentaT[0]['num_comprobante'];
                    me.tax = arrayVentaT[0]['impuesto'];
                    me.total = arrayVentaT[0]['total'];
                })
                .catch(function (error) {
                    console.log(error);
                });

                //Obtener los datos de los detalles 
               var urld= '/venta/obtenerDetalles?id=' + id;
                axios.get(urld).then(function (response) {
                    var respuesta= response.data;
                    me.arrayDetalle = respuesta.detalles;
                })
                .catch(function (error) {
                    console.log(error);
                });
  
            },
            closeModal(){
                this.modal =0;
                this.tittlemodal='';
                
            },
            openModal(){
                this.arrayArticle =[];
                this.modal = 1;
                this.tittlemodal = 'Seleccionar Articulo';
                                  
            },
            deactivateVenta(id){
                 swal({
                title: 'Esta seguro de anular esta venta?',
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

                    axios.put('/venta/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listVenta(1,'','num_voucher');
                        swal(
                        'Anulado!',
                        'La venta ha sido anulada con éxito.',
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
            this.listVenta(1, this.buscar, this.criterion);
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


