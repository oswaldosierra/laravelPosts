<div class="max-w-7xl mx-auto sm:px-6 lg:px-8" wire:init="loadsPosts">
    <div class="w-full mt-5">
        <div class="py-3 px-3 flex items-center">
            <div class="flex items-center">
                <span>Mostrar</span>
                <select wire:model="cant"
                    class="mx-2 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>Entradas</span>

            </div>
            <x-jet-input class="flex-1 mx-4 shadow-md rounded" placeholder="Buscador..." type="text"
                wire:model="buscador" />
            @livewire('create-post')
        </div>
        <div class="bg-white max-w-7xl shadow-md rounded my-3 w-full">
            @if (count($posts))
                <table class="min-w-max w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th wire:click="order('id')" class="cursor-pointer py-3 px-6 text-left flex">id
                                @if ($sort == 'id')
                                    @if ($direction == 'asc')
                                        <i class="fa fa-sort-amount-asc ml-1"></i>
                                    @else
                                        <i class="fa fa-sort-amount-desc ml-1"></i>
                                    @endif
                                @endif
                            </th>
                            <th wire:click="order('title')" class="cursor-pointer py-3 px-6 text-left">Title
                                @if ($sort == 'title')
                                    @if ($direction == 'asc')
                                        <i class="fa fa-sort-alpha-asc ml-1"></i>
                                    @else
                                        <i class="fa fa-sort-alpha-desc ml-1"></i>
                                    @endif
                                @endif
                            </th>
                            <th wire:click="order('content')" class="cursor-pointer py-3 px-6 text-center">Content
                                @if ($sort == 'content')
                                    @if ($direction == 'asc')
                                        <i class="fa fa-sort-alpha-desc ml-1"></i>
                                    @else
                                        <i class="fa fa-sort-alpha-asc ml-1"></i>
                                    @endif
                                @endif
                            </th>
                            <th class="cursor-pointer py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @foreach ($posts as $items)
                            <tr class="border-b border-gray-200 bg-gray-50 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <span class="font-medium">{{ $items->id }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <div class="flex items-center">
                                        <span>{{ $items->title }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex items-center">
                                        <span>{{ Str::substr($items->content, 0, 30) }}...</span>
                                    </div>
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center">
                                        {{-- @livewire('edit-post', ['post' => $post->id], key($post->id)) --}}
                                        <div wire:click="edit({{ $items }})"
                                            class="w-4 mr-2 transform hover:text-yellow-500 cursor-pointer hover:scale-110">
                                            <i class="fa fa-edit" title="Edit post"></i>
                                        </div>
                                        <div wire:click="$emit('deletePost', '{{ $items->id }}')"
                                            class="w-4 mr-2 transform hover:text-yellow-500 cursor-pointer hover:scale-110">
                                            <i class="fa fa-trash" title="Edit post"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if ($posts->hasPages())
                    <div class="px-6 py-3">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="py-3 px-6">
                    No se encontraron datos
                </div>
            @endif


        </div>
    </div>
    <x-jet-dialog-modal wire:model="open_edit">

        <x-slot name="title">
            Editar el post {{ $post->title }}
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-jet-label value="Titulo del post" />
                <x-jet-input wire:model="post.title" type="text" class="w-full" />
                <x-jet-input-error for="post.title" />
            </div>

            <div class="mb-4" wire:ignore>
                <x-jet-label value="Contenido del post" />
                <x-text-area wire:model="post.content" id="editor2" class="w-full">{{ $post->content }}</x-text-area>
                <x-jet-input-error for="post.content" />
            </div>

            <div class="mb-4">
                <input type="file" wire:model="image" id="{{ $identificador }}">
                <x-jet-input-error for="image" />
            </div>

            {{-- Alerta de carga de imagen --}}
            <div wire:loading wire:target="image"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Imagen Cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen cargue...</span>
            </div>

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" alt="">
            @else
                <img src="{{ Storage::url($post->image) }}" alt="">
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open_edit',false)">
                Cancelar
            </x-jet-secondary-button>
            <x-jet-danger-button wire:click="update" wire:target="update, image" wire:loading.attr="disabled">
                Actualizar
            </x-jet-danger-button>
        </x-slot>

    </x-jet-dialog-modal>

    @push('js')
        <script>
            ClassicEditor
                .create(document.querySelector('#editor2'))
                .then(function(editor) {
                    editor.model.document.on('change:data', () => {
                        @this.set('content', editor.getData());
                    })
                })
                .catch(error => {
                    console.error(error);
                });
        </script>
        <script>
            Livewire.on('deletePost', postId => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('show-posts', 'delete', postId);
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush
</div>
