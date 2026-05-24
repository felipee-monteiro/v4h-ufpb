import { reactive, toRefs } from 'vue';
import type { Teleconsultoria } from '@/types';

const state = reactive<{
    selectedTeleconsultoria: Teleconsultoria | null;
    detailDialogOpen: boolean;
}>({
    selectedTeleconsultoria: null,
    detailDialogOpen: false
});

export function useDetalheTeleconsultoriaModal() {
    function openDetalhesTeleconsultoriaModal(teleconsultoria: Teleconsultoria) {
        state.detailDialogOpen = true;
        state.selectedTeleconsultoria = teleconsultoria;
    }

    function closeDetailsModal() {
        state.detailDialogOpen = false;
        state.selectedTeleconsultoria = null;
    }

    return {
        ...toRefs(state),
        openDetalhesTeleconsultoriaModal,
        closeDetailsModal
    };
}
