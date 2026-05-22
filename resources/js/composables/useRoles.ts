import { readonly, ref } from 'vue'

export type RoleKey = 'Solicitante' | 'Especialista'

export type RoleItem = {
  key: RoleKey
  label: string
}

const internal = ref<RoleItem[]>([
  { key: 'Solicitante', label: 'Solicitante' },
  { key: 'Especialista', label: 'Especialista' },
])

export const roles = readonly(internal)

export function getRoleLabel(key?: RoleKey) {
  return internal.value.find(r => r.key === key)?.label ?? ''
}

export function useRoles() {
  return {
    roles,
    getRoleLabel,
  }
}

export default useRoles
