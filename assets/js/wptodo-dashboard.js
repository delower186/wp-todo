jQuery(document).ready(function($){
    // Fetch todos
    $.post(wptodo_dashboard.ajax_url, {
        action: 'get_todos',
        nonce: wptodo_dashboard.nonce
    }, function(response){
        if(!response.success) return;

        const todos = response.data;

        // === Kanban ===
        const statuses = ['Not Started','In Progress','Pending','In Review','Completed','Cancelled'];
        const kanbanContainer = $('#wptodo-kanban');

        statuses.forEach(status => {
            const col = $('<div class="kanban-col" style="flex:1;border:1px solid #ddd;padding:10px;border-radius:5px;background:#f9f9f9;"></div>');
            col.append(`<h3 style="text-align:center;">${status}</h3>`);
            const list = $('<div class="kanban-list"></div>').attr('data-status', status);
            col.append(list);
            kanbanContainer.append(col);

            // Make column sortable
            new Sortable(list[0], {
                group: 'todos',
                animation: 150,
                onEnd: function(evt){
                    const todoId = $(evt.item).data('id');
                    const newStatus = $(evt.to).data('status');

                    $.post(wptodo_dashboard.ajax_url, {
                        action: 'update_todo_status',
                        todo_id: todoId,
                        status: newStatus,
                        nonce: wptodo_dashboard.nonce
                    });
                }
            });
        });

        // Add todos as cards
        todos.forEach(todo => {
            let color;
            switch(todo.priority){
                case 'Low': color='#d4edda'; break;
                case 'Normal': color='#cce5ff'; break;
                case 'High': color='#ffe5b4'; break;
                case 'Important': color='#f8d7da'; break;
                default: color='#f8f9fa';
            }

            const card = $(`
                <div class="kanban-card" data-id="${todo.id}" style="background:${color};margin:5px;padding:10px;border-radius:5px;cursor:pointer;">
                    <strong>${todo.title}</strong><br>
                    <small>${todo.priority} | ${todo.date}</small>
                </div>
            `);

            $(`.kanban-list[data-status="${todo.status}"]`).append(card);
        });

        // === Calendar ===
        const calendarEl = document.createElement('div');
        $('#wptodo-calendar').append(calendarEl);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            events: todos.map(todo => ({
                id: todo.id,
                title: todo.title,
                start: todo.date,
            }))
        });
        calendar.render();
    });
});
