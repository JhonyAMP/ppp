<div>
    <x-dialog-modal wire:model="isOpenAssign" maxWidth="sm">
        <x-slot name="title">
            <i class="fa-solid fa-user-lock mr-2"></i>
            {{ $isOpenAssign && $supervisor ? 'Actualizar supervisor del estudiante' : 'Asignar supervisor al estudiante' }}
        </x-slot>
        <x-slot name="content">
            <form autocomplete="off">
                <div class="mb-3 text-center">
                    <span class="bg-gradient-to-r from-blue-800 to-blue-700 text-white text-sm px-4 py-1 rounded-md">
                        {{ $user ? $user->name . ' ' . $user->surnames : '' }}
                    </span>
                </div>
                <x-app.label value="LISTA DE SUPERVISORES:" class="font-bold my-1" />
                <div class="flex justify-center items-center">
                    <div class="px-2">
                        @foreach ($supervisores as $supervisor)
                            <div class="flex w-full items-center mb-1">
                                <label htmlFor="checkbox"
                                    class="relative flex items-center pr-2 rounded-full cursor-pointer">
                                    <input wire:model.live="supervisor" type="checkbox" value="{{ $supervisor->id }}"
                                        class="before:content[''] peer relative h-4 w-4 cursor-pointer appearance-none rounded border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-9 before:w-9 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity focus:ring-0 hover:checked:bg-gray-900 focus:checked:bg-gray-900 checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900 hover:before:opacity-10" />
                                </label>
                                <label class="text-sm font-medium text-gray-900">
                                    {{ $supervisor->name }} {{ $supervisor->surnames }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </form>
        </x-slot>
        <x-slot name="footer">
            <button data-ripple-dark="true" x-on:click="show = false" wire:click="closeModals"
                class="px-4 py-2.5 mr-1 font-sans text-xs font-bold text-red-500 uppercase transition-all rounded-lg middle none center hover:bg-red-500/10 active:bg-red-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                Cancelar
            </button>

            <x-button-gradient color="green" wire:click.prevent="asignarSupervisor({{ $user }})"
                wire:loading.attr="disabled" wire:target="asignarSupervisor">
                <span wire:loading wire:target="asignarSupervisor" class="mr-2">
                    <i class="fa fa-spinner fa-spin"></i>
                </span>
                {{ $isOpenAssign && $supervisor ? 'Actualizar' : 'Asignar' }}
            </x-button-gradient>
        </x-slot>

    </x-dialog-modal>
</div>
