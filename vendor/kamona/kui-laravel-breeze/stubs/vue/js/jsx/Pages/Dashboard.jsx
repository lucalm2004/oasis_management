import { defineComponent } from 'vue'
import AuthenticatedLayout from '@/Layouts/Authenticated'
import Button from '@/Components/Button'
import { GithubIcon } from '@/Components/Icons/brands'

const Header = defineComponent({
    setup() {
        return () => (
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <h2 class="text-xl font-semibold leading-tight">Dashboard</h2>

                <Button
                    external
                    variant="black"
                    target="_blank"
                    class="items-center gap-2 max-w-xs"
                    href="https://github.com/kamona-wd/kui-laravel-breeze"
                >
                    {({ iconSizeClasses }) => (
                        <>
                            <GithubIcon
                                aria-hidden="true"
                                class={iconSizeClasses}
                            />
                            <span>Star on Github</span>
                        </>
                    )}
                </Button>
            </div>
        )
    },
})

export default defineComponent({
    setup() {
        return () => (
            <AuthenticatedLayout
                title="Dashboard"
                v-slots={{ header: () => <Header /> }}
            >
                <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
                    You're logged in!!
                </div>
            </AuthenticatedLayout>
        )
    },
})
