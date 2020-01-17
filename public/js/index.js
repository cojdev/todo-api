qs('#due').valueAsDate = new Date(Date.now() + (24 * 60 * 60 * 1000));
    const form = document.getElementById('add-form')
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      addTask(e.currentTarget.value);
    })
    const output = qs('.output');
    const taskCount = qs('#task-count');

    function addTask(id) {
      const data = {
        description: form.querySelector('[name=description]').value,
        starred: form.querySelector('[name=starred]').checked,
        due: form.querySelector('[name=due]').value,
      };
      
      ajax(`/task`, 'POST', data)
      .then(data => {
        console.log(data.parsed);
        loadTasks();
      })
      .catch(data => {
        console.log(data.parsed);
      });
    }

    function deleteTask(id) {
      ajax(`/task/${id}`, 'DELETE')
      .then(data => {
        console.log(data.parsed);
        loadTasks();
      });
    }
    
    function loadTasks(limit = 0) {
      ajax(`/task${limit ? `?limit=${limit}` : ''}`, 'GET')
      .then(data => {
        const json = data.parsed;
        output.innerHTML = 
        `<table class="table">
          <tr>
            <th>id</th>
            <th>Description</th>
            <th>Due</th>
            <th>Starred</th>
            <th>Completed</th>
            <th>Delete</th>
          </tr>
          ${json.data.map(item => 
          `<tr>
            <td>${item.id}</td>
            <td>${item.description}</td>
            <td>${item.due}</td>
            <td>${item.starred}</td>
            <td>${item.completed}</td>
            <td><button class="button" data-id="${item.id}">Delete</button></td>
          </tr>`).join('')}
        </table>`;
        taskCount.innerText = `${json.data.length} (${json.data.filter(item => item.completed !== null).length} completed)`;

        document.querySelectorAll('.delete-button').forEach(item => {item.addEventListener('click', function (e) {
          deleteTask(e.currentTarget.getAttribute('data-id'));
        })});
      });
    }

    loadTasks();