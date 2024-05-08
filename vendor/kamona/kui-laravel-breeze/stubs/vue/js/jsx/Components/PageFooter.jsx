import { defineComponent } from 'vue'
import { HeartIcon } from '@heroicons/vue/solid'

export default defineComponent({
    setup() {
        return () => (
            <footer class="flex-shrink-0 px-6 py-4">
                <p class="flex items-center justify-center gap-1 text-sm text-gray-600  dark:text-gray-400">
                    <span>Made with</span>
                    <span>
                        <HeartIcon class="w-6 h-6 text-red-500" />
                        <span className="sr-only">Love</span>
                    </span>
                    <span>by</span>
                    <a
                        href="https://github.com/Kamona-WD"
                        target="_blank"
                        class="text-blue-600 hover:underline"
                    >
                        Ahmed Kamel
                    </a>
                </p>
            </footer>
        )
    },
})
