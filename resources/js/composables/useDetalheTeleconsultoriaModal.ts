import { reactive, toRefs } from 'vue';
import type { Teleconsultoria } from '@/types';

const state = reactive<{
    selectedTeleconsultoria: Teleconsultoria | null;
    detailDialogOpen: boolean;
    professionalOpinion: string;
}>({
    selectedTeleconsultoria: null,
    detailDialogOpen: false,
    professionalOpinion: '',
});

export function useDetalheTeleconsultoriaModal() {
    function openDetalhesTeleconsultoriaModal(teleconsultoria: Teleconsultoria) {
        state.detailDialogOpen = true;
        state.selectedTeleconsultoria = teleconsultoria;
    }

    function closeDetailsModal() {
        state.selectedTeleconsultoria = null;
        state.detailDialogOpen = false;
    }

    return {
        ...toRefs(state),
        openDetalhesTeleconsultoriaModal,
        closeDetailsModal
    };
}
