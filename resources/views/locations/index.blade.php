<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ubicaciones
        </h2>
    </x-slot>
    <div id="app">
        <x-container class="py-8">
            {{-- Formulario para crear Ubicaciones --}}
            <x-form-section class="mb-12">
                <x-slot name="title">
                    Agregar ubicación
                </x-slot>
                <x-slot name="description">
                    Ingrese los siguientes datos para crear una nueva ubicación
                </x-slot>
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 sm:col-span-6">
                        <div v-if="createForm.errors.length > 0" class="mb-4 bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                            <strong class="font-bold">Error. </strong>
                            <span>Hubo un error al guardar los datos</span>
                            <ul>
                                <li v-for="error in createForm.errors">@{{error}}</li>
                            </ul>
                        </div>
                        <x-input-label>
                            Nombre
                        </x-input-label>
                        <x-text-input v-model="createForm.name" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Tipo
                        </x-input-label>
                        <x-text-input v-model="createForm.type" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Dimensión
                        </x-input-label>
                        <x-text-input v-model="createForm.dimension" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            URL
                        </x-input-label>
                        <x-text-input v-model="createForm.slug" type="text" class="w-full mt-1"/>
                    </div>
                </div>
                <x-slot name="actions">
                    <x-primary-button v-bind:disabled="createForm.disabled" v-on:click="store">
                        Crear
                    </x-primary-button>
                </x-slot>
            </x-form-section>
            {{-- Error de ubicaciones --}}
            <div v-if="locationsError.length > 0"  class="mb-4 bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                <strong class="font-bold">Error. </strong>
                <span>Hubo un error al cargar los datos</span>
                <ul>
                    <li v-for="error in locationsError">@{{error}}</li>
                </ul>
            </div>
            {{-- Listado de ubicaciones --}}
            <x-form-section v-if="locations.length > 0">
                <x-slot name="title">
                    Listado de ubicaciones
                </x-slot>
                <x-slot name="description">
                    Estos son las ubicaciones que se han agregado
                </x-slot>
                <div>
                    <table class="text-gray-300">
                        <thead class="border-b border-gray-700">
                            <tr class="text-left">
                                <th class="py-2 w-full">Nombre</th>
                                <th class="py-2">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-gray-200">
                            <tr v-for="location in locations">
                                <td class="py-2">
                                    @{{location.name}}
                                </td>
                                <td class="py-2 flex divide-x divide-gray-700">
                                    <a v-on:click="show(location)"
                                    class="pr-2 hover:text-green-600 font-semibold cursor-pointer">
                                        Ver
                                    </a>
                                    <a v-on:click="edit(location)"
                                    class="px-2 hover:text-blue-600 font-semibold cursor-pointer">
                                        Editar
                                    </a>
                                    <a v-on:click="destroy(location)" 
                                    class="pl-2 hover:text-red-600 font-semibold cursor-pointer">
                                        Eliminar
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-form-section>

            
        </x-container>
        {{-- MODAL VER --}}
        <x-dialog-modal modal="showLocation.open">
            <x-slot name="title">
                Información de la ubicación
            </x-slot>
            <x-slot name="content">
                <div class="space-y-2">
                    <p>
                        <span class="font-semibold">Nombre: </span>
                        <span v-text="showLocation.name"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Tipo: </span>
                        <span v-text="showLocation.type"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Dimensión: </span>
                        <span v-text="showLocation.dimension"></span>
                    </p>
                    <p>
                        <span class="font-semibold">URL: </span>
                        <span v-text="showLocation.slug"></span>
                    </p>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button v-on:click="showLocation.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cerrar</button>
            </x-slot>
        </x-dialog-modal>

        {{-- MODAL EDITAR --}}
        <x-dialog-modal modal="editLocation.open">
            <x-slot name="title">
                Editar ubicación
            </x-slot>
            <x-slot name="content">
                <div class="space-y-6">
                    <div v-if="editLocation.errors.length > 0" class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Error. </strong>
                        <span>Hubo un error al guardar los datos</span>
                        <ul>
                            <li v-for="error in editLocation.errors">@{{error}}</li>
                        </ul>
                    </div>
                    <div>
                        <x-input-label-white>
                            Nombre
                        </x-input-label-white>
                        {{-- <x-text-input-white v-model="editCharacter.name" type="text" class="w-full mt-1"/> --}}
                        <x-text-input-white v-model="editLocation.name" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Tipo
                        </x-input-label-white>
                        <x-text-input-white v-model="editLocation.type" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Dimensión
                        </x-input-label-white>
                        <x-text-input-white v-model="editLocation.dimension" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            URL
                        </x-input-label-white>
                        <x-text-input-white v-model="editLocation.slug" type="text" class="w-full mt-1"/>
                    </div>
                    
                </div>
            </x-slot>
            <x-slot name="footer">
                <button v-bind:disabled="editLocation.disabled" v-on:click="update()" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-50">Actualizar</button>
                <button v-on:click="editLocation.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
            </x-slot>
        </x-dialog-modal>
    </div>
    @push('js')

    <script>
        const { createApp } = Vue;
            createApp({
                data() {
                    return{
                        locations:[],
                        locationsError:[],
                        createForm:{
                            name: null,
                            type: null,
                            dimension: null,
                            slug: null,
                            disabled: false,
                            errors: [],
                        },
                        editLocation:{
                            id: null,
                            name: null,
                            type: null,
                            dimension: null,
                            slug: null,
                            disabled: false,
                            errors: [],
                        },
                        showLocation:{
                            open:false,
                            name: null,
                            type: null,
                            dimension: null,
                            slug: null, 
                        },
                    }
                },
                mounted(){
                    this.getLocations();
                },
                methods:{
                    getLocations(){
                        axios.get('/locations')
                        .then(response => {
                            this.locations = Object.values(response.data).flat();
                        })
                        .catch(error =>{
                            this.locationsError = Object.values(error.response.data).flat();
                        });;
                    },
                    show(location){
                        this.showLocation.open = true;
                        this.showLocation.name = location.name;
                        this.showLocation.type = location.type;
                        this.showLocation.dimension = location.dimension;
                        this.showLocation.slug = location.slug;
                    },
                    store(){
                        Swal.fire({
                            title: 'Guardando información',
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        this.createForm.disabled = true;
                        axios.post('/locations',this.createForm)
                        .then(response => {
                            Swal.close();
                            this.createForm.name = null;
                            this.createForm.type = null;
                            this.createForm.dimension = null;
                            this.createForm.slug = null;
                            this.createForm.errors = [];
                            this.createForm.disabled = false;
                            this.getLocations();
                        })
                        .catch(error =>{
                            Swal.close();
                            this.createForm.errors = Object.values(error.response.data).flat();
                            this.createForm.disabled = false;
                        });
                    },
                    edit(location){
                        this.editLocation.open = true;
                        this.editLocation.id = location.id;
                        this.editLocation.name = location.name;
                        this.editLocation.type = location.type;
                        this.editLocation.dimension = location.dimension;
                        this.editLocation.slug = location.slug;
                        this.editLocation.errors = [];
                        this.editLocation.disabled = false;
                    },
                    update(){
                        this.editLocation.disabled = true;
                        Swal.fire({
                            title: 'Guardando información',
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        axios.put('/locations/'+this.editLocation.id,this.editLocation)
                        .then(response => {
                            Swal.close();
                            this.editLocation.disabled = false;
                            this.editLocation.open = false;
                            this.editLocation.id = null;
                            this.editLocation.name = null;
                            this.editLocation.type = null;
                            this.editLocation.dimension = null;
                            this.editLocation.slug = null;
                            this.editLocation.errors = [];
                            Swal.fire(
                                'Actualizado!',
                                'Los datos fueron actualizados',
                                'success'
                            );
                            this.getLocations();
                        })
                        .catch(error =>{
                            Swal.close();
                            this.editLocation.errors = Object.values(error.response.data.errors).flat();
                            this.editLocation.disabled = false;
                        });
                        
                    },
                    destroy(location){
                        Swal.fire({
                            title: '¿Esta seguro?',
                            text: "Esto no se podrá revertir",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Borrando información',
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                });
                                axios.delete('/locations/'+character.id)
                                .then(response => {
                                    Swal.close();
                                    Swal.fire(
                                        'Eliminado!',
                                        'El registro se eliminó correctamente.',
                                        'success'
                                    );
                                    this.getLocations();
                                })
                                .catch(error =>{
                                    this.charactersError = Object.values(error.response.data).flat();
                                });
                            }
                        });
                    },
                }
            }).mount('#app');
    </script>
    @endpush
</x-app-layout>
