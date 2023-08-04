<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Personajes
        </h2>
    </x-slot>
    <div id="app">
        <x-container class="py-8">
            {{-- Formulario para crear Personajes --}}
            <x-form-section class="mb-12">
                <x-slot name="title">
                    Agregar personaje
                </x-slot>
                <x-slot name="description">
                    Ingrese los siguientes datos para crear un nuevo personaje
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
                            Estado
                        </x-input-label>
                        <select v-model="createForm.status" class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="1">Vivo</option>
                            <option value="2">Muerto</option>
                            <option value="3">Desconocido</option>
                        </select>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Especie
                        </x-input-label>
                        <x-text-input v-model="createForm.species" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Tipo
                        </x-input-label>
                        <x-text-input v-model="createForm.type" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Genero
                        </x-input-label>
                        <x-text-input v-model="createForm.gender" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            URL
                        </x-input-label>
                        <x-text-input v-model="createForm.slug" type="text" class="w-full mt-1"/>
                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Ubicación
                        </x-input-label>
                        <div v-for="location in locations">
                            <x-input-label class="col-span-2 sm:col-span-6">
                                <input type="checkbox" name="scopes" :value="location.id" v-model="createForm.location"/>
                                @{{location.name}}
                            </x-input-label> 
                        </div>

                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Episodios
                        </x-input-label>
                        <div v-for="episode in episodes">
                            <x-input-label class="col-span-2 sm:col-span-6">
                                <input type="checkbox" name="scopes" :value="episode.id" v-model="createForm.episodes"/>
                                @{{episode.id}} - @{{episode.name}}
                            </x-input-label> 
                        </div>

                    </div>
                    <div class="col-span-6 sm:col-span-6">
                        <x-input-label>
                            Origen
                        </x-input-label>
                        <select v-model="createForm.origin" class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option v-for="location in locations" :value="location.id">@{{location.name}}</option>
                        </select>
                    </div>
                </div>
                <x-slot name="actions">
                    <x-primary-button v-bind:disabled="createForm.disabled" v-on:click="store">
                        Crear
                    </x-primary-button>
                </x-slot>
            </x-form-section>
            {{-- Error de personajes --}}
            <div v-if="charactersError.length > 0" class="mb-4 bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                <strong class="font-bold">Error. </strong>
                <span>Hubo un error al cargar los datos</span>
                <ul>
                    <li v-for="error in charactersError">@{{error}}</li>
                </ul>
            </div>
            {{-- Listado de personajes --}}
            <x-form-section v-if="characters.length > 0">
                <x-slot name="title">
                    Listado de personajes
                </x-slot>
                <x-slot name="description">
                    Estos son los personajes que se han agregado
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
                            <tr v-for="character in characters">
                                <td class="py-2">
                                    @{{character.name}}
                                </td>
                                <td class="py-2 flex divide-x divide-gray-700">
                                    <a v-on:click="show(character)"
                                    class="pr-2 hover:text-green-600 font-semibold cursor-pointer">
                                        Ver
                                    </a>
                                    <a v-on:click="edit(character)"
                                    class="px-2 hover:text-blue-600 font-semibold cursor-pointer">
                                        Editar
                                    </a>
                                    <a v-on:click="destroy(character)" 
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
        <x-dialog-modal modal="showCharacter.open">
            <x-slot name="title">
                Información del personaje
            </x-slot>
            <x-slot name="content">
                <div class="space-y-2">
                    <p>
                        <span class="font-semibold">Nombre: </span>
                        <span v-text="showCharacter.name"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Estado: </span>
                        <span v-text="showCharacter.status"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Especie: </span>
                        <span v-text="showCharacter.species"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Tipo: </span>
                        <span v-text="showCharacter.type"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Genero: </span>
                        <span v-text="showCharacter.gender"></span>
                    </p>
                    <p>
                        <span class="font-semibold">URL: </span>
                        <span v-text="showCharacter.slug"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Ubicación: </span>
                        <span v-text="showCharacter.location"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Episodios: </span>
                        <span v-text="showCharacter.episode"></span>
                    </p>
                    <p>
                        <span class="font-semibold">Origen: </span>
                        <span v-text="showCharacter.origin"></span>
                    </p>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button v-on:click="showCharacter.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cerrar</button>
            </x-slot>
        </x-dialog-modal>

        {{-- MODAL EDITAR --}}
        <x-dialog-modal modal="editCharacter.open">
            <x-slot name="title">
                Editar cliente
            </x-slot>
            <x-slot name="content">
                <div class="space-y-6">
                    <div v-if="editCharacter.errors.length > 0" class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                        <strong class="font-bold">Error. </strong>
                        <span>Hubo un error al guardar los datos</span>
                        <ul>
                            <li v-for="error in editCharacter.errors">@{{error}}</li>
                        </ul>
                    </div>
                    <div>
                        <x-input-label-white>
                            Nombre
                        </x-input-label-white>
                        <x-text-input-white v-model="editCharacter.name" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                        </x-input-label-white>
                        <select v-model="editCharacter.status" class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="1">Vivo</option>
                            <option value="2">Muerto</option>
                            <option value="3">Desconocido</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label-white>
                            Especie
                        </x-input-label-white>
                        <x-text-input-white v-model="editCharacter.species" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Tipo
                        </x-input-label-white>
                        <x-text-input-white v-model="editCharacter.type" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Genero
                        </x-input-label-white>
                        <x-text-input-white v-model="editCharacter.gender" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            URL
                        </x-input-label-white>
                        <x-text-input-white v-model="editCharacter.slug" type="text" class="w-full mt-1"/>
                    </div>
                    <div>
                        <x-input-label-white>
                            Ubicación
                        </x-input-label-white>
                        <div v-for="location in locations">
                            <x-input-label-white class="col-span-2 sm:col-span-6">
                                <input v-model="editCharacter.location" type="checkbox" name="scopes" :value="location.id" v-model="editCharacter.location"/>
                                @{{location.name}}
                            </x-input-label-white> 
                        </div>
                    </div>
                    <div>
                        <x-input-label-white>
                            Episodios
                        </x-input-label-white>
                        <div v-for="episode in episodes">
                            <x-input-label-white class="col-span-2 sm:col-span-6">
                                <input v-model="editCharacter.episodes" type="checkbox" name="scopes" :value="episode.id" v-model="editCharacter.episodes"/>
                                @{{episode.id}} - @{{episode.name}}
                            </x-input-label-white> 
                        </div>
                    </div>
                    <div>
                        <x-input-label-white>
                            Origen
                        </x-input-label-white>
                        <select v-model="editCharacter.origin" class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option v-for="location in locations" :value="location.id">@{{location.name}}</option>
                        </select>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button v-bind:disabled="editCharacter.disabled" v-on:click="update(editCharacter)" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-50">Actualizar</button>
                <button v-on:click="editCharacter.open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancelar</button>
            </x-slot>
        </x-dialog-modal>

    </div>
    @push('js')
        <script>
            const { createApp } = Vue;
            createApp({
                data() {
                    return{
                        status:{
                            1: 'Vivo',
                            2: 'Muerto',
                            3: 'Desconocido'
                        },
                        locations:[],
                        createForm:{
                            name: null,
                            status: 1,
                            species: null,
                            type: null,
                            gender: null,
                            slug: null,
                            location: [],
                            episodes: [],
                            origin: null,
                            errors:[],
                            disabled: false,
                        },
                        characters:[],
                        episodes:[],
                        charactersError:[],
                        showCharacter:{
                            open:false,
                            name: null,
                            status: null,
                            species: null,
                            type: null,
                            gender: null,
                            slug: null,
                            location: null,
                            origin: null,
                            episode: null,
                        },
                        editCharacter:{
                            id: null,
                            open: false,
                            name: null,
                            status: 1,
                            species: null,
                            type: null,
                            gender: null,
                            slug: null,
                            location: [],
                            episodes: [],
                            origin: null,
                            errors:[],
                            disabled: false,
                        },
                    }
                },
                mounted(){
                    this.getLocations();
                    this.getCharacters();
                    this.getEpisodes();
                },
                methods:{
                    getLocations(){
                        axios.get('/locations')
                        .then(response => {
                            this.locations = Object.values(response.data).flat();
                        });
                    },
                    getCharacters(){
                        axios.get('/characters')
                        .then(response => {
                            this.characters = Object.values(response.data).flat();
                        })
                        .catch(error =>{
                            this.charactersError = Object.values(error.response.data).flat();
                        });
                    },
                    getEpisodes(){
                        axios.get('/episodes')
                        .then(response => {
                            this.episodes = Object.values(response.data).flat();
                        });
                    },
                    store(){
                        Swal.fire({
                            title: 'Guardando información',
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        this.createForm.disabled = true;
                        axios.post('/characters',this.createForm)
                        .then(response => {
                            Swal.close();
                            this.createForm.name = null;
                            this.createForm.status = 1;
                            this.createForm.species = null;
                            this.createForm.type = null;
                            this.createForm.gender = null;
                            this.createForm.slug = null;
                            this.createForm.location = [];
                            this.createForm.episodes = [];
                            this.createForm.origin = null;
                            this.createForm.disabled = false;
                            this.createForm.errors = [];
                            this.getCharacters();
                        })
                        .catch(error =>{
                            Swal.close();
                            this.createForm.errors = Object.values(error.response.data).flat();
                            this.createForm.disabled = false;
                        });
                    },
                    show(character){
                        let txtLocation = '';
                        character.locations.forEach(element => {
                            txtLocation += element.name + ", ";
                        });
                        let txtEpisode = '';
                        character.episodes.forEach(element => {
                            txtEpisode += element.name + ", ";
                        });
                        this.showCharacter.open = true;
                        this.showCharacter.name = character.name;
                        this.showCharacter.status = this.status[character.status];
                        this.showCharacter.species = character.species;
                        this.showCharacter.type = character.type;
                        this.showCharacter.gender = character.gender;
                        this.showCharacter.slug = character.url;
                        this.showCharacter.location = txtLocation;
                        this.showCharacter.episode = txtEpisode;

                        txtOrigin = this.locations.filter((item) => {
                            return (item.id == character.origin_id)
                        });
                        this.showCharacter.origin = txtOrigin[0].name;
                    },
                    edit(character){
                        this.editCharacter.open = true;
                        this.editCharacter.id = character.id;
                        this.editCharacter.name = character.name;
                        this.editCharacter.status = character.status;
                        this.editCharacter.species = character.species;
                        this.editCharacter.type = character.type;
                        this.editCharacter.gender = character.gender;
                        this.editCharacter.slug = character.url;
                        let arrLocations = [];
                        character.locations.forEach(element => {
                            arrLocations.push(element.id);
                        });
                        let arrEpisodes = [];
                        character.episodes.forEach(element => {
                            arrEpisodes.push(element.id);
                        });
                        this.editCharacter.location = arrLocations;
                        this.editCharacter.episodes = arrEpisodes;
                        this.editCharacter.origin = character.origin_id;
                    },
                    update(character){
                        Swal.fire({
                            title: 'Guardando información',
                            didOpen: () => {
                                Swal.showLoading()
                            }
                        });
                        this.editCharacter.disabled = true;
                        axios.put('/characters/'+this.editCharacter.id,this.editCharacter)
                        .then(response => {
                            Swal.close();
                            this.editCharacter.open = false;
                            this.editCharacter.id = null;
                            this.editCharacter.name = null;
                            this.editCharacter.status = null;
                            this.editCharacter.species = null;
                            this.editCharacter.type = null;
                            this.editCharacter.gender = null;
                            this.editCharacter.slug = null;
                            this.editCharacter.location = [];
                            this.editCharacter.episodes = [];
                            this.editCharacter.origin = null;
                            this.editCharacter.disabled = false;
                            this.editCharacter.errors = [];
                            Swal.fire(
                                'Actualizado!',
                                'Los datos fueron actualizados',
                                'success'
                            );
                            this.getCharacters();
                        })
                        .catch(error =>{
                            this.editCharacter.errors = Object.values(error.response.data.errors).flat();
                            this.editCharacter.disabled = false;
                        });
                        
                    },
                    destroy(character){
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
                                axios.delete('/characters/'+character.id)
                                .then(response => {
                                    Swal.close();
                                    Swal.fire(
                                        'Eliminado!',
                                        'El registro se eliminó correctamente.',
                                        'success'
                                    );
                                    this.getCharacters();
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
