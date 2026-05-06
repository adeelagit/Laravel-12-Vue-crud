import { usePage } from '@inertiajs/vue3'
import type { PageProps } from '@/types'

export function usePermission() {
    const page = usePage<PageProps>()

    const permissions = page.props.auth.permissions

    const can = (permission: string): boolean => {
        return permissions.includes(permission)
    }

    return { can }
}