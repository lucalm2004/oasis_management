import {
    defineComponent,
    computed,
    onMounted,
    onUnmounted,
    watch,
    Teleport,
    Transition,
} from 'vue'

export default defineComponent({
    props: {
        show: {
            type: Boolean,
            default: false,
        },
        maxWidth: {
            type: String,
            default: '2xl',
        },
        closeable: {
            type: Boolean,
            default: true,
        },
    },

    emits: ['close'],

    setup(props, { slots, emit }) {
        watch(
            () => props.show,
            () => {
                if (props.show) {
                    document.body.style.overflow = 'hidden'
                } else {
                    document.body.style.overflow = null
                }
            }
        )

        const close = () => {
            if (props.closeable) {
                emit('close')
            }
        }

        const closeOnEscape = (e) => {
            if (e.key === 'Escape' && props.show) {
                close()
            }
        }

        onMounted(() => document.addEventListener('keydown', closeOnEscape))

        onUnmounted(() => {
            document.removeEventListener('keydown', closeOnEscape)
            document.body.style.overflow = null
        })

        const maxWidthClass = computed(() => {
            return {
                sm: 'sm:max-w-sm',
                md: 'sm:max-w-md',
                lg: 'sm:max-w-lg',
                xl: 'sm:max-w-xl',
                '2xl': 'sm:max-w-2xl',
            }[props.maxWidth]
        })

        return () => (
            <Teleport to="body">
                <Transition leave-active-class="duration-200">
                    <div
                        v-show={props.show}
                        class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
                        scroll-region
                    >
                        <Transition
                            enter-active-class="ease-out duration-300"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="ease-in duration-200"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <div
                                v-show={props.show}
                                class="fixed inset-0 transform transition-all"
                                onClick={() => {
                                    close()
                                }}
                            >
                                <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" />
                            </div>
                        </Transition>

                        <Transition
                            enter-active-class="ease-out duration-300"
                            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                            leave-active-class="ease-in duration-200"
                            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        >
                            <div
                                v-show={props.show}
                                class={[
                                    'mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto',
                                    maxWidthClass.value,
                                ]}
                            >
                                {props.show && slots.default?.()}
                            </div>
                        </Transition>
                    </div>
                </Transition>
            </Teleport>
        )
    },
})
