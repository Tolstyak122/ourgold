<script>
    import { defineComponent, ref, computed, onMounted } from "vue";
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from '@inertiajs/inertia'
    import Datepicker from '@vuepic/vue-datepicker';
    import '@vuepic/vue-datepicker/dist/main.css'

    export default defineComponent({
        name: "Locations",
        components: {
            Head,
            Link,
            Datepicker,
        },
        setup(props, { emit, attrs }) {

            const query_room = attrs.room;
            const appartments = ref([]);
            const locations = ref([]);
            const room_types = ref({});
            const furniture_types = ref({});
            const furniture_sets  = ref({});
            const furnitures  = ref({});
            const loading = ref(true);
            const filters = ref({
                appartment: 0,
                room: query_room,
                arrival: '',
                departure: '',
                present_date: '',
            });

            const apply_filter = () => {
                loading.value = true;
                axios
                    .get(route('api.locations', filters.value))
                    .then(res => {
                        locations.value = res.data;
                        loading.value = false;
                    });
            }

            const locations_tree = computed(() => {
                const room_ids = Object.keys(locations.value.reduce((acc, item) => ({[item.room_id]: 1, ...acc}), {}));
                const response = appartments.value.reduce((acc, appartment) => {
                    const rooms = appartment.rooms.filter(room => room_ids.includes(`${room.id}`));
                    if(rooms.length) {
                        const _appartment = JSON.parse(JSON.stringify(appartment));

                        _appartment.rooms = JSON.parse(JSON.stringify(rooms));
                        _appartment.rooms.forEach(room => {
                            room.locations = locations.value.filter(location => location.room_id == room.id);
                        });

                        acc.push(_appartment);
                    }
                    return acc;
                }, []);
                return response;
            });

            const loader = () => {
                loading.value = true;
                axios
                    .get(route('api.room_types'))
                    .then(res => {
                        room_types.value = res.data.reduce((acc, item) => ({[item.id]: item.title, ...acc}), {});
                        return axios.get(route('api.furniture_types'));
                    })
                    .then(res => {
                        furniture_types.value = res.data.reduce((acc, item) => ({[item.id]: item.title, ...acc}), {});
                        return axios.get(route('api.furniture_sets'));
                    })
                    .then(res => {
                        furniture_sets.value = res.data.reduce((acc, item) => ({[item.id]: item.title, ...acc}), {});
                        return axios.get(route('api.furnitures'));
                    })
                    .then(res => {
                        furnitures.value = res.data.reduce((acc, item) => ({[item.id]: item, ...acc}), {});
                        return axios.get(route('api.appartments'));
                    })
                    .then(res => {
                        appartments.value = res.data;
                        if(query_room) {
                            return axios.get(route('api.locations', {room: query_room}));
                        } else {
                            loading.value = false;
                            return false;
                        }
                    }).then(res => {
                        if(res) {
                            locations.value = res.data;
                            loading.value = false;
                        }
                    })
            }

            const onSuccessListener = Inertia.on('success', ev => {
                if(ev.detail.page.component == 'Locations') {
                    loader();
                }
            })

            onMounted(() => loader());

            return { appartments, loading, room_types, furniture_types, furniture_sets,
                     locations, filters, apply_filter, query_room, locations_tree, furnitures, }
        }
    });
</script>

<template>
    <Head title="Locations" />
    <h3 v-show="loading">Loading data...</h3>
    <template v-if="!loading">
        <p>
            <Link :href="route('/')">Back to dashboard</Link>
        </p>
        <p>
            <label for="appartment" v-if="!query_room">
                Appartment:
                <select
                    id="appartment"
                    v-model="filters.appartment"
                    @change="filters.room = 0"
                >
                    <option
                        v-for="(appartment, indx) in appartments"
                        :key="appartment.id"
                        :value="appartment.id"
                    >
                        {{ appartment.title }}
                    </option>
                </select>
            </label>
            <h3 v-else>
                {{ appartments.find(item => item.rooms.find(room => room.id == query_room)).title }}<br />
                {{ appartments.find(item => item.rooms.find(room => room.id == query_room)).rooms.find(room => room.id == query_room).title }}
            </h3>
            <label for="room" v-if="filters.appartment" style="margin-left: 10px;">
                Room:
                <select
                    id="room"
                    v-model="filters.room"
                >
                    <option
                        v-for="(room, indx) in appartments.find(item => item.id == filters.appartment).rooms"
                        :key="room.id"
                        :value="room.id"
                    >
                        {{ room.title }}
                    </option>
                </select>
            </label><br v-if="filters.appartment" />
            <label for="present_date">
                Показать мебель на дату:
                <Datepicker
                    v-model="filters.present_date"
                    cancelText="Отмена"
                    selectText="Выбрать"
                    :enableTimePicker="true"
                    :dayNames="['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']"
                />
            </label><br />
            <label for="arrival">
                Arrival:
                <Datepicker
                    v-model="filters.arrival"
                    cancelText="Отмена"
                    selectText="Выбрать"
                    :enableTimePicker="true"
                    :dayNames="['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']"
                />
            </label><br />
            <label for="departure">
                Departure:
                <Datepicker
                    v-model="filters.departure"
                    cancelText="Отмена"
                    selectText="Выбрать"
                    :enableTimePicker="true"
                    :dayNames="['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс']"
                />
            </label><br />
            <button @click="apply_filter">Filter</button>
        </p>
        <hr/>
        <ul>
            <li v-for="(location, indx) in locations_tree" :key="location.id">
                <p>{{ location.title }}</p>
                <ol>
                    <li v-for="(room, r_indx) in location.rooms" :key="room.id">
                        <span style="margin-right: 10px;">{{ room.title }} [{{ room_types[room.room_type_id] }}]</span>
                        <table>
                            <thead>
                                <tr>
                                    <th style="padding: 2px 15px;">Title</th>
                                    <th style="padding: 2px 15px;">Set</th>
                                    <th style="padding: 2px 15px;">Type</th>
                                    <th style="padding: 2px 15px;">Arrival</th>
                                    <th style="padding: 2px 15px;">Departure</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(location, l_indx) in room.locations" :key="location.id">
                                    <td style="padding: 2px 15px;">{{ furnitures[location.furniture_id].title }}</td>
                                    <td style="padding: 2px 15px;">{{ furniture_sets[furnitures[location.furniture_id].furniture_set_id] }}</td>
                                    <td style="padding: 2px 15px;">{{ furniture_types[furnitures[location.furniture_id].furniture_type_id] }}</td>
                                    <td style="padding: 2px 15px;">{{ (new Date(location.arrival)).toLocaleString() }}</td>
                                    <td style="padding: 2px 15px;">{{ location.departure ? (new Date(location.departure)).toLocaleString() : '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </li>
                </ol>
            </li>
        </ul>
    </template>
</template>
