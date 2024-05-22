<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group Chat</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Create Group Chat</h1>
    <form id="createGroupForm">
        @csrf <!-- Agregar el token CSRF aquÃ­ -->
        <label for="groupName">Group Name:</label><br>
        <input type="text" id="groupName" name="group_name" required><br><br>

        <label for="userIds">User IDs (comma separated):</label><br>
        <input type="text" id="userIds" name="user_ids" required><br><br>

        <label for="avatar">Avatar:</label><br>
        <input type="file" id="avatar" name="avatar"><br><br>

        <button type="submit">Create Group</button>
    </form>

    <script>
        $(document).ready(function(){

            $('#createGroupForm').submit(function(event){
                event.preventDefault();
                var formData = new FormData($(this)[0]);
                console.log('Form Data:');
                formData.forEach((value, key) => {
                    console.log(key + ': ' + value);
                });
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('group-chat.create') }}",
                    type: 'POST',
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response.status == 1){
                            alert('Group created successfully!');
                            // Redirect or do something else on success
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(){
                        alert('Error creating group. Please try again later.');
                    }
                });
                return false;
            });
        });
    </script>
</body>
</html>
