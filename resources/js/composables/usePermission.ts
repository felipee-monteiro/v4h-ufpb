import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function usePermission() {
    const page = usePage();
    const permissions = computed(() => page.props.auth.permissions);

    function hasPermission(permission: string): boolean {
        return true === permissions.value[permission];
    }

    return {
        hasPermission
    };
}
