
const SUPABASE_URL = "https://kljlrchsawiteqearbgy.supabase.co";
const SUPABASE_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImtsamxyY2hzYXdpdGVxZWFyYmd5Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3Nzc2Njg3ODQsImV4cCI6MjA5MzI0NDc4NH0.scWeGSJmm7xgxKg6F2sGqMTQsIATdX58AB0ZSZrXnvI";

const supabaseClient = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

const { createApp } = Vue;

createApp({
    data() {
        return {
            dishes: [],
            newDish: {
                name: '',
                price: ''
            }
        }
    },

    methods: {
        async fetchDishes() {
            const { data, error } = await supabaseClient
                .from('platos')
                .select('*');

            if (error) {
                console.error(error);
            } else {
                this.dishes = data;
            }
        },

        async addDish() {
            if (!this.newDish.name || !this.newDish.price) {
                alert("Fill all fields");
                return;
            }

            const { error } = await supabaseClient
                .from('platos')
                .insert([{
                    nombre: this.newDish.name,
                    precio: this.newDish.price
                }]);

            if (error) {
                console.error(error);
            } else {
                this.fetchDishes();
                this.newDish.name = '';
                this.newDish.price = '';
            }
        }
    },

    mounted() {
        this.fetchDishes();
    }

}).mount("#app");