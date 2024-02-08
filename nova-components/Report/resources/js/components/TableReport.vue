<template>
    <div>
        <div v-if="hasBorder" class="py-3 px-3 flex items-center border-b border-50"></div>
        
        <div class="relative">
            <template v-if="hasRecord">
                <div class="overflow-hidden overflow-x-auto relative">
                    <table
                        class="table w-full table-default"
                        data-testid="resource-table"
                        cellpadding="0"
                        cellspacing="0"
                    >
                        <thead>
                            <slot name="header" />
                        </thead>
                        <tbody>
                            <slot name="body" />
                        </tbody>
                    </table>
                </div>
                <slot name="pagination" />
            </template>
            
            <no-record-view v-else />
        </div>
    </div>
</template>

<script>
export default {
    props: {
        hasRecord: {
            type: Boolean,
            required: true
        },

        hasBorder: {
            type: Boolean,
            default: true
        }
    }
}
</script>