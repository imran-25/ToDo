<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">To-Do List</h1>
        
        <!-- Add new To-Do -->
        <div class="input-group mb-3">
            <input type="text" id="newTodoTitle" class="form-control" placeholder="New Task">
            <button class="btn btn-primary" id="addTodoButton">Add Task</button>
        </div>
        
        <!-- To-Do List -->
        <ul class="list-group" id="todoList">
            <!-- Items will be dynamically added here -->
        </ul>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing To-Dos on page load
            fetchTodos();

            // Fetch To-Do items
            function fetchTodos() {
                $.get('/api/todos', function(data) {
                    $('#todoList').empty();
                    data.forEach(function(todo) {
                        appendTodoToList(todo);
                    });
                });
            }

            // Add To-Do item
            $('#addTodoButton').click(function() {
                let title = $('#newTodoTitle').val();
                if (title !== '') {
                    $.post('/api/todos', { title: title }, function(todo) {
                        appendTodoToList(todo);
                        $('#newTodoTitle').val(''); // Clear input
                    });
                }
            });

            // Append To-Do item to the list
            function appendTodoToList(todo) {
                let todoItem = `
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-id="${todo.id}">
                        <span class="todo-title">${todo.title}</span>
                        <div>
                            <button class="btn btn-sm btn-success completeTodoButton" data-completed="${todo.completed}">${todo.completed ? 'Completed' : 'Complete'}</button>
                            <button class="btn btn-sm btn-danger deleteTodoButton">Delete</button>
                        </div>
                    </li>
                `;
                $('#todoList').append(todoItem);
            }

            // Mark To-Do item as completed
            $(document).on('click', '.completeTodoButton', function() {
                let todoItem = $(this).closest('li');
                let todoId = todoItem.data('id');
                let isCompleted = $(this).data('completed');

                $.ajax({
                    url: `/api/todos/${todoId}`,
                    type: 'PUT',
                    data: { completed: !isCompleted },
                    success: function(updatedTodo) {
                        let button = todoItem.find('.completeTodoButton');
                        button.text(updatedTodo.completed ? 'Completed' : 'Complete');
                        button.data('completed', updatedTodo.completed);
                    }
                });
            });

            // Delete To-Do item
            $(document).on('click', '.deleteTodoButton', function() {
                let todoItem = $(this).closest('li');
                let todoId = todoItem.data('id');

                $.ajax({
                    url: `/api/todos/${todoId}`,
                    type: 'DELETE',
                    success: function() {
                        todoItem.remove();
                    }
                });
            });
        });
    </script>
</body>
</html>
