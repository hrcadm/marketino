<div
    x-data="notificationHandler()"
    class="fixed inset-0 flex flex-col-reverse items-end justify-start h-screen w-screen"
    @notification.window="add($event.detail)"
    style="pointer-events:none">
    <template x-for="notification of notifications" :key="notification.id">
        <div
            x-show="visible.includes(notification)"
            x-transition:enter="transition ease-in duration-200"
            x-transition:enter-start="transform opacity-0 translate-y-2"
            x-transition:enter-end="transform opacity-100"
            x-transition:leave="transition ease-out duration-500"
            x-transition:leave-start="transform translate-x-0 opacity-100"
            x-transition:leave-end="transform translate-x-full opacity-0"
            @click="remove(notification.id)"
            class="rounded mb-4 mr-6 w-60  h-20 flex items-center text-center justify-center text-white shadow-lg font-bold text-lg cursor-pointer"
            :class="{
				'bg-indigo-500': notification.type === 'success',
				'bg-red-500': notification.type === 'error',
			 }"
            style="pointer-events:all"
            x-text="notification.text">
        </div>
    </template>
</div>

@push('scripts')
    <script>
        function notificationHandler() {
            return {
                notifications: [],
                visible: [],
                add(notification) {
                    notification.id = Date.now()
                    this.notifications.push(notification)
                    this.fire(notification.id)
                },
                fire(id) {
                    this.visible.push(this.notifications.find(notification => notification.id == id))
                    const timeShown = 2000 * this.visible.length
                    setTimeout(() => {
                        this.remove(id)
                    }, timeShown)
                },
                remove(id) {
                    const notification = this.visible.find(notification => notification.id == id)
                    const index = this.visible.indexOf(notification)
                    this.visible.splice(index, 1)
                },

            };
        }

    </script>
@endpush
