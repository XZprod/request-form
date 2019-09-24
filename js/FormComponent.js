let component = {
    template: `
<div id="user-edit-form">
<form action="#">
    Имя: <br><input type="text" name="name" v-model="request.name"><br>
    Телефон:<br> <input type="text" name="phone" v-model="request.phone"><br>
    Сообщение: <br><textarea name="message" v-model="request.message"></textarea><br>
    <button v-on:click.stop.prevent @click="createRequest(request)">Создать</button>
</form>
</div>`,
    data: function () {
        return {
            groups: [],
            request: {}
        }
    },
    created() {
        // this.updateList()
    },

    methods: {
        createRequest: function (request) {
            fetch('index.php', {
                method: 'POST', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, cors, *same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: {
                    'Content-Type': 'application/json',
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: 'follow', // manual, *follow, error
                referrer: 'no-referrer', // no-referrer, *client
                body: JSON.stringify(request), // тип данных в body должен соответвовать значению заголовка "Content-Type"
            }).then(function (response) {
                return response.json();
            }).then(function (myJson) {
                if (myJson.result === 'success') alert('Успех!!11');
            });
        },
    }
};
export default component;