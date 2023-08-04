<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Episodios
        </h2>
    </x-slot>
    <div id="app">
        <x-container class="py-8">
            {{-- Formulario para crear episodios --}}
            <x-form-section class="mb-12">
                <x-slot name="title">
                    Agregar episodio
                </x-slot>
                <x-slot name="description">
                    Ingrese los siguientes datos para crear un nuevo episodio
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
                            Fecha salida al aire
                        </x-input-label>
                        <x-text-input v-model="createForm.air_date" type="datetime-local" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Episodio (iniciales)
                        </x-input-label>
                        <x-text-input v-model="createForm.episode" type="text" class="w-full mt-1"/>
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
            {{-- Error de episodios --}}
            <div v-if="episodesError.length > 0"  class="mb-4 bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                <strong class="font-bold">Error. </strong>
                <span>Hubo un error al cargar los datos</span>
                <ul>
                    <li v-for="error in episodesError">@{{error}}</li>
                </ul>
            </div>
            {{-- Listado de episodios --}}
            <x-form-section v-if="episodes.length > 0">
                <x-slot name="title">
                    Listado de episodios
                </x-slot>
                <x-slot name="description">
                    Estos son los episodios que se han agregado
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
                            <tr v-for="episode in episodes">
                                <td class="py-2">
                                    @{{episode.name}}
                                </td>
                                <td class="py-2 flex divide-x divide-gray-700">
                                    <a v-on:click="show(episode)"
                                    class="pr-2 hover:text-green-600 font-semibold cursor-pointer">
                                        Ver
                                    </a>
                                    <a v-on:click="edit(episode)"
                                    class="px-2 hover:text-blue-600 font-semibold cursor-pointer">
                                        Editar
                                    </a>
                                    <a v-on:click="destroy(episode)" 
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
        <x-dialog-modal modal="showEpisode.open">
            <x-slot name="title">
                Información del episodio
            </x-slot>
            <x-slot name="content">
                <div class="space-y-2">
                    <p>
                        <span class="font-semibold">Nombre: </span>
                        <span v-text="showEpisode.name"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Fecha salida al aire: </span>
                        <span v-text="showEpisode.air_date"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Episodio (iniciales): </span>
                        <span v-text="showEpisode.episode"></span>
                    </p>
                    <p>
                        <span class="font-semibold">URL: </span>
                        <span v-text="showEpisode.slug"></span>
                    </p>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button v-on:click="showEpisode.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cerrar</button>
            </x-slot>
        </x-dialog-modal>

        {{-- MODAL EDITAR --}}
        <x-dialog-modal modal="editEpisode.open">
            <x-slot name="title">
                Editar ubicación
            </x-slot>
            <x-slot name="content">
                <div class="space-y-6">
                    <div v-if="editEpisode.errors.length > 0" class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Error. </strong>
                        <span>Hubo un error al guardar los datos</span>
                        <ul>
                            <li v-for="error in editEpisode.errors">@{{error}}</li>
                        </ul>
                    </div>
                    <div>
                        <x-input-label-white>
                            Nombre
                        </x-input-label-white>
                        {{-- <x-text-input-white v-model="editCharacter.name" type="text" class="w-full mt-1"/> --}}
                        <x-text-input-white v-model="editEpisode.name" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Fecha salida al aire
                        </x-input-label-white>
                        <x-text-input-white v-model="editEpisode.air_date" type="datetime-local" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Episodio (iniciales)
                        </x-input-label-white>
                        <x-text-input-white v-model="editEpisode.episode" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            URL
                        </x-input-label-white>
                        <x-text-input-white v-model="editEpisode.slug" type="text" class="w-full mt-1"/>
                    </div>
                    
                </div>
            </x-slot>
            <x-slot name="footer">
                <button v-bind:disabled="editEpisode.disabled" v-on:click="update()" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-50">Actualizar</button>
                <button v-on:click="editEpisode.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
            </x-slot>
        </x-dialog-modal>

    </div>
    @push('js')
    <script>
        const { createApp } = Vue;
            createApp({
                data() {
                    return{
                        episodes:[],
                        episodesError:[],
                        createForm:{
                            name: null,
                            air_date: null,
                            episode: null,
                            slug: null,
                            disabled: false,
                            errors: [],
                        },
                        showEpisode:{
                            open:false,
                            name: null,
                            air_date: null,
                            episode: null,
                            slug: null, 
                        },
                        editEpisode:{
                            id: null,
                            name: null,
                            air_date: null,
                            episode: null,
                            slug: null,
                            disabled: false,
                            errors: [],
                        },
                    }
                },
                mounted(){
                    this.getEpisodes();
                },
                methods:{
                    getEpisodes(){
                        axios.get('/episodes')
                        .then(response => {
                            this.episodes = Object.values(response.data).flat();
                        })
                        .catch(error =>{
                            this.episodesError = Object.values(error.response.data).flat();
                        });;
                    },
                    show(episode){
                        this.showEpisode.open = true;
                        this.showEpisode.name = episode.name;
                        this.showEpisode.air_date = episode.air_date;
                        this.showEpisode.episode = episode.episode;
                        this.showEpisode.slug = episode.slug;
                    },
                    store(){
                        Swal.fire({
                            title: 'Guardando información',
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        this.createForm.disabled = true;
                        axios.post('/episodes',this.createForm)
                        .then(response => {
                            Swal.close();
                            this.createForm.name = null;
                            this.createForm.air_date = null;
                            this.createForm.episode = null;
                            this.createForm.slug = null;
                            this.createForm.errors = [];
                            this.createForm.disabled = false;
                            this.getEpisodes();
                        })
                        .catch(error =>{
                            Swal.close();
                            this.createForm.errors = Object.values(error.response.data).flat();
                            this.createForm.disabled = false;
                        });
                    },
                    edit(episode){
                        this.editEpisode.open = true;
                        this.editEpisode.id = episode.id;
                        this.editEpisode.name = episode.name;
                        this.editEpisode.air_date = episode.air_date;
                        this.editEpisode.episode = episode.episode;
                        this.editEpisode.slug = episode.slug;
                        this.editEpisode.errors = [];
                        this.editEpisode.disabled = false;
                    },
                    update(){
                        this.editEpisode.disabled = true;
                        Swal.fire({
                            title: 'Guardando información',
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        axios.put('/episodes/'+this.editEpisode.id,this.editEpisode)
                        .then(response => {
                            Swal.close();
                            this.editEpisode.disabled = false;
                            this.editEpisode.open = false;
                            this.editEpisode.id = null;
                            this.editEpisode.name = null;
                            this.editEpisode.air_date = null;
                            this.editEpisode.episode = null;
                            this.editEpisode.slug = null;
                            this.editEpisode.errors = [];
                            Swal.fire(
                                'Actualizado!',
                                'Los datos fueron actualizados',
                                'success'
                            );
                            this.getEpisodes();
                        })
                        .catch(error =>{
                            Swal.close();
                            this.editEpisode.errors = Object.values(error.response.data.errors).flat();
                            this.editEpisode.disabled = false;
                        });
                        
                    },
                    destroy(episode){
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
                                axios.delete('/episodes/'+episode.id)
                                .then(response => {
                                    Swal.close();
                                    Swal.fire(
                                        'Eliminado!',
                                        'El registro se eliminó correctamente.',
                                        'success'
                                    );
                                    this.getEpisodes();
                                })
                                .catch(error =>{
                                    this.episodesError = Object.values(error.response.data).flat();
                                });
                            }
                        });
                    },
                }
            }).mount('#app');
    </script>
    @endpush
</x-app-layout>
