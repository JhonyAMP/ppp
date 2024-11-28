<x-dialog-modal wire:model="isOpenConfig" maxWidth="lg">
    <x-slot name="title">
        <i class="fa-solid fa-clock mr-2"></i>
        Configurar fechas
    </x-slot>
    <x-slot name="content">
        <form autocomplete="off">
            <div class="flex flex-col gap-3">
                @if ($dates)
                    @foreach ($dates as $date)
                        <div class="grid grid-cols-2 gap-3">
                            <x-input-label label="Nombre" value="{{ $date->nombre }}" disabled />
                            <x-input-label for="form.phone" label="Fecha" wire:model.live="fechas.{{ $date->id }}"
                                type="date" />
                        </div>
                    @endforeach
                @endif
            </div>
        </form>
    </x-slot>
    <x-slot name="footer">
        <button data-ripple-dark="true" x-on:click="show = false" wire:click="closeModals"
            class="px-4 py-2.5 mr-1 font-sans text-xs font-bold text-red-500 uppercase transition-all rounded-lg middle none center hover:bg-red-500/10 active:bg-red-500/30 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
            Cancelar
        </button>

        <x-button-gradient color="green" wire:click="actualizarFechas()" wire:loading.attr="disabled" wire:target="actualizarFechas">
            <span wire:loading wire:target="actualizarFechas" class="mr-2">
                <i class="fa fa-spinner fa-spin"></i>
            </span>
            {{ $itemId ? 'Actualizar' : 'Registrar' }}
        </x-button-gradient>
    </x-slot>
</x-dialog-modal>
