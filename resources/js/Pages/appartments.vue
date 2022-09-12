<script>
    import { defineComponent, ref, computed, onMounted } from "vue";
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia'

    export default defineComponent({
        name: "Appartments",
        components: {
            Head,
            Link,
        },
        setup(props, { emit, attrs }) {

            const appartments = ref([]);
            const room_types = ref({});
            const loading = ref(true);

            const loader = () => {
                loading.value = true;
                axios
                    .get(route('api.room_types'))
                    .then(res => {
                        room_types.value = res.data.reduce((acc, item) => ({[item.id]: item.title, ...acc}), {});
                        return axios.get(route('api.appartments'));
                    })
                    .then(res => {
                        appartments.value = res.data;
                        loading.value = false;
                    })
            }

            const onSuccessListener = Inertia.on('success', ev => {
                if(ev.detail.page.component == 'Appartments') {
                    loader();
                }
            })

            onMounted(() => loader());

            return { appartments, loading, room_types }
        }
    });
</script>

<template>
    <Head title="Appartments" />
    <h3 v-show="loading">Loading data...</h3>
    <template v-if="!loading">
        <p>
            <Link :href="route('/')">Back to dashboard</Link>
        </p>
        <hr/>
        <ul>
            <li v-for="(appartment, indx) in appartments" :key="appartment.id">
                <p>{{ appartment.title }}</p>
                <ol>
                    <li v-for="(room, r_indx) in appartment.rooms" :key="room.id">
                        <span style="margin-right: 10px;">{{ room.title }} [{{ room_types[room.room_type_id] }}]</span>
                        <Link :href="route('locations', {room_id: room.id})">Open locations</Link>
                    </li>
                </ol>
            </li>
        </ul>
    </template>
</template>
