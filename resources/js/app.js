import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import XLSX from 'xlsx';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('notifications')
    .listen('.NewNotification', (event) => {  // Notice the dot before NewNotification
        console.log("🔔 New Notification:", event.message);
        alert(event.message);
    });

    // Listen for the 'new-request' event
window.Echo.channel('requests-channel')
.listen('.new-request', (data) => {
    console.log('New request received:', data);

    // Dynamically add the new request to the table
    const tableBody = document.querySelector('#requests-table tbody');
    const newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td>${data.request.unique_code}</td>
        <td>${data.request.part_number}</td>
        <td>${data.request.part_name}</td>
        <td>${data.request.description}</td>
        <td>${data.request.revision_type}</td>
        <td>${data.request.uph}</td>
        <td>${data.request.standard_yield_percentage}</td>
        <td>${data.request.standard_yield_dollar_per_hour}</td>
        <td>${data.request.actual_yield_percentage}</td>
        <td>${data.request.actual_yield_dollar_per_hour}</td>
        <td>${data.request.process_type}</td>
        <td>${data.request.current_process_index}</td>
        <td>${data.request.total_processes}</td>
        <td>${data.request.manager_1_status}</td>
        <td>${data.request.manager_2_status}</td>
        <td>${data.request.manager_3_status}</td>
        <td>${data.request.manager_4_status}</td>
        <td>${new Date(data.request.created_at).toLocaleString()}</td>
    `;

    tableBody.prepend(newRow); // Add the new row to the top of the table
});