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
        const kanbanContainer = $('#wptodo-kanban').empty();

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
            // Card color based on priority
            let cardColor = '#f8f9fa', priorityBadge = '';
            switch(todo.priority){
                case 'Low':    cardColor='#d4edda'; priorityBadge='green'; break;
                case 'Normal': cardColor='#cce5ff'; priorityBadge='blue'; break;
                case 'High':   cardColor='#ffe5b4'; priorityBadge='orange'; break;
                case 'Critical': cardColor='#f8d7da'; priorityBadge='red'; break;
            }

            // Check overdue
            let isOverdue = false;
            if (todo.deadline) {
                const today = new Date();
                const deadlineDate = new Date(todo.deadline + 'T23:59:59'); // Ensure full day
                isOverdue = deadlineDate < today;
            }

            // Recurring badge with overdue color
            let recurringBadge = '';
            if (todo.recurring) {
                let bgColor = isOverdue && todo.status !== "Completed" ? '#dc3545' : '#3F9B0B'; // Red if overdue, green otherwise
                recurringBadge = `<span class="recurring-badge" 
                    style="background:${bgColor};color:#fff;padding:2px 5px;border-radius:3px;font-size:11px;margin-left:5px;">
                    ${todo.recurring_label}
                </span>`;
            }

            // Deadline badge
            let deadlineBadge = '';
            if(todo.deadline) {
                deadlineBadge = `<span class="deadline-badge" 
                    style="background:#dc3545;color:#fff;padding:2px 5px;border-radius:3px;font-size:11px;margin-left:5px;">
                    ${todo.deadline}
                </span>`;
            }

            const card = $(`
                <div class="kanban-card" data-id="${todo.id}" 
                    style="background:${cardColor};margin:5px;padding:10px;border-radius:5px;cursor:pointer;">
                    <strong>${todo.title}</strong><br>
                    <small>
                        <span class="priority-label" 
                            style="display:inline-block;padding:2px 6px;border-radius:4px;
                                   background:${priorityBadge};color:#fff;font-size:11px;">
                            ${todo.priority}
                        </span>
                        ${deadlineBadge}
                        ${recurringBadge}
                    </small>
                </div>
            `);

            $(`.kanban-list[data-status="${todo.status}"]`).append(card);
        });

        // === Calendar ===
        const calendarEl = document.createElement('div');
        $('#wptodo-calendar').empty().append(calendarEl);

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            events: todos.map(todo => {
                let startDate = todo.date || todo.deadline;
                let endDate = todo.deadline || todo.date;

                // Add 1 day to make FullCalendar include the deadline
                if (endDate) {
                    let end = new Date(endDate);
                    end.setDate(end.getDate() + 1);
                    endDate = end.toISOString().split('T')[0]; // convert back to YYYY-MM-DD
                }

                return {
                    id: todo.id,
                    title: todo.title,
                    start: startDate,
                    end: endDate,
                    extendedProps: {
                        priority: todo.priority,
                        recurring: todo.recurring,
                        deadline: todo.deadline
                    }
                };
            }),
            eventDidMount: function(info) {
                // Set event background based on priority
                const priorityColors = {
                    'Low': '#28a745',
                    'Normal': '#007bff',
                    'High': '#fd7e14',
                    'Critical': '#dc3545'
                };
                const bgColor = priorityColors[info.event.extendedProps.priority] || '#6c757d';
                info.el.style.backgroundColor = bgColor;
                info.el.style.color = '#fff'; // ensure text contrast

                // Remove default text color for badges
                const el = info.el.querySelector('.fc-event-title');
                if(!el) return;

                // Priority badge (white bg, black text)
                if(info.event.extendedProps.priority){
                    const span = document.createElement('span');
                    span.style.cssText = 'background:#670B9C;color:#fff;padding:2px 5px;margin-left:5px;border-radius:3px;font-size:11px;';
                    span.textContent = info.event.extendedProps.priority;
                    el.appendChild(span);
                }

                // Recurring badge (white bg, black text)
                if(info.event.extendedProps.recurring){
                    const span = document.createElement('span');
                    span.style.cssText = 'background:#3F9B0B;color:#fff;padding:2px 5px;margin-left:5px;border-radius:3px;font-size:11px;';
                    span.textContent = 'Recurring';
                    el.appendChild(span);
                }

                // Deadline badge (white bg, black text)
                if(info.event.extendedProps.deadline){
                    const span = document.createElement('span');
                    span.style.cssText = 'background:#1589FF;color:#fff;padding:2px 5px;margin-left:5px;border-radius:3px;font-size:11px;';
                    span.textContent = info.event.extendedProps.deadline;
                    el.appendChild(span);
                }
            }
        });
        calendar.render();
    });
});
