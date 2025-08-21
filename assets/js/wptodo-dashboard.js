jQuery(document).ready(function($){
    // Fetch todos
    $.post(wptodo_dashboard.ajax_url, {
        action: 'get_todos',
        nonce: wptodo_dashboard.nonce
    }, function(response){
        if(!response.success) return;

        const todos = response.data;

        // === Kanban ===
        const statuses = ['Not Started','In Progress','In Review','Completed'];
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
            let cardColor, badgeColor;
            switch(todo.priority){
                case 'Low':
                    cardColor  = '#d4edda';  // light green
                    badgeColor = '#28a745';  // green
                    break;
                case 'Normal':
                    cardColor  = '#cce5ff';  // light blue
                    badgeColor = '#007bff';  // blue
                    break;
                case 'High':
                    cardColor  = '#ffe5b4';  // light orange
                    badgeColor = '#fd7e14';  // orange
                    break;
                case 'Critical':
                    cardColor  = '#f8d7da';  // light red
                    badgeColor = '#dc3545';  // red
                    break;
                default:
                    cardColor  = '#f8f9fa';  // light gray
                    badgeColor = '#6c757d';  // gray
            }

            const card = $(`
                <div class="kanban-card" data-id="${todo.id}" 
                    style="background:${cardColor};margin:5px;padding:10px;border-radius:5px;cursor:pointer;">
                    <strong>${todo.title}</strong><br>
                    <small>
                        <span class="priority-label" 
                            style="display:inline-block;padding:2px 6px;border-radius:4px;
                                    background:${badgeColor};color:#fff;font-size:11px;">
                            ${todo.priority}
                        </span>
                        ${
                            todo.deadline 
                                ? `<span class="deadline-label" 
                                        style="display:inline-block;padding:2px 6px;margin-left:5px;
                                                border-radius:4px;background:#dc3545;color:#fff;font-size:11px;">
                                    ${todo.deadline}
                                </span>` 
                                : ""
                        }
                    </small>
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
