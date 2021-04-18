(function(w,d) {
    const container = d.getElementById('container');

    let displayList = function() {
        if (!container) { return; }
        ajax.loadEntries(function(response) {
            let entries = response.data;
            container.innerHTML = renderer.renderEntries(entries);
        }, function() {
            alert('Error loading entries');
        });
    };

    let ajax = {
        loadEntries: function(successHandler, errorHandler) {
            this.call('GET', './listExpenses', {}, successHandler, errorHandler);
        },
        createEntry: function(data, successHandler, errorHandler) {
            this.call('POST', './expenses/', data, successHandler, errorHandler);
        },
        updateEntry: function(data, successHandler, errorHandler) {
            this.call('PATCH', './expenses/'+data.id, data, successHandler, errorHandler);
        },
        deleteEntry: function(data, successHandler, errorHandler) {
            this.call('DELETE', './expenses/'+data.id, {}, successHandler, errorHandler);
        },

        call: function(method, url, data, successHandler, errorHandler) {
            if (method === 'GET') {
                url += '?' + Object.keys(data).map(function(key) {return key+'='+data[key];}).join('&');
            }
            let xhr = new XMLHttpRequest();
            xhr.open(method, url);
            if (method === 'GET') {
                xhr.send();
            } else {
                xhr.send(JSON.stringify(data));
            }

            xhr.onload = function() {
                let response = {};
                if (xhr.status == 200) {
                    response = JSON.parse(xhr.response);
                }
                if (response.status == 'success') {
                    successHandler(response);
                } else {
                    errorHandler(response);
                }
            };

            xhr.onerror = function() {
                errorHandler({'message': 'An error occurred'}); //better error handling obviously required here.
            };
        }
    };

    let eventHandler = {
        setup: function() {
            let handler = this;
            d.querySelectorAll('.btn').forEach(function(button) {
                let eventName = button.id.replace('btn_','');
                button.addEventListener('click', handler[eventName]);
            });
        },

        create: function(event) {
            console.log('create clicked!');
            let data = {
                'description': d.getElementById('c_description').value,
                'value': parseInt(d.getElementById('c_value').value)
            };
            ajax.createEntry(data, function() {
                displayList();
                alert('New entry created.');
            }, function() {
                alert('Error creating entry!');
            });
        },

        update: function(event) {
            console.log('update clicked!');
            let data = {
                'id': d.getElementById('u_id').value,
                'description': d.getElementById('u_description').value,
                'value': parseInt(d.getElementById('u_value').value)
            };
            ajax.updateEntry(data, function(response) {
                displayList();
                alert('Entry updated.');
            }, function() {
                alert('Error updating entry!');
            });
        },

        remove: function(event) {   //'remove' because 'delete' is a JavaScript keyword
            console.log('delete clicked!');
            let data = {
                'id': d.getElementById('d_id').value
            };
            ajax.deleteEntry(data, function(response) {
                displayList();
                alert('Entry deleted.');
            }, function() {
                alert('Error deleting entry!');
            });
        }
    };

    let renderer = {
        renderEntries: function(entries) {
            let tableRows = [];
            entries.forEach(function(entry) {
                let row = "<tr>"
                        + "<td>"+entry.id+"</td>"
                        + "<td>"+entry.description+"</td>"
                        + "<td>"+entry.value+"</td>"
                        + "</tr>";
                tableRows.push(row);
            });

            return "<table>"
                 + "<tr><th>ID</th><th>Description</th><th>Value</th></tr>"
                 + tableRows.join('')
                 + "</table>";
        },
    }

    eventHandler.setup();
    displayList();
})(window, document);
