<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Game</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            padding-top: 50px;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
            border: 2px solid #ff6347;
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #ffe4e1;
        }
        .card-header {
            background-color: #ff6347;
            color: #fff;
            font-size: 1.5em;
            border-radius: 15px 15px 0 0;
        }
        .btn-primary {
            background-color: #32cd32;
            border: none;
        }
        .btn-primary:hover {
            background-color: #228b22;
        }
        .user-card {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ff6347;
            border-radius: 10px;
            background-color: #fff;
        }
        .user-card img {
            border-radius: 50%;
            margin-right: 15px;
        }
        .user-list {
            margin-top: 30px;
        }
        .form-control {
            border-radius: 10px;
        }
        .form-group label {
            color: #ff6347;
        }
        #startGameBtn {
            background-color: #1e90ff;
            border: none;
        }
        #startGameBtn:hover {
            background-color: #1c86ee;
        }
        #nextUserBtn {
            background-color: #ff4500;
            border: none;
        }
        #nextUserBtn:hover {
            background-color: #ff6347;
        }
        @media (max-width: 767px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            .card-header {
                font-size: 1.2em;
            }
            .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Register
            </div>
            <div class="card-body">
                <form id="registerForm">
                    <div class="form-group">
                        <label for="regUsername">Username</label>
                        <input type="text" class="form-control" id="regUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="regPassword">Password</label>
                        <input type="password" class="form-control" id="regPassword" name="pass1" required>
                    </div>
                    <div class="form-group">
                        <label for="regConfirmPassword">Confirm Password</label>
                        <input type="password" class="form-control" id="regConfirmPassword" name="pass2" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Login
            </div>
            <div class="card-body">
                <form id="loginForm">
                    <div class="form-group">
                        <label for="loginUsername">Username</label>
                        <input type="text" class="form-control" id="loginUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>

        <div class="card" id="hostGameCard" style="display;">
            <div class="card-header">
                Host a Game
            </div>
            <div class="card-body">
                <form id="hostGameForm">
                    <div class="form-group">
                        <label for="roomName">Room Name</label>
                        <input type="text" class="form-control" id="roomName" name="roomname" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Host Game</button>
                </form>
            </div>
        </div>

        <div class="card" id="setQuestionCard" style="display:;">
            <div class="card-header">
                Set Questions
            </div>
            <div class="card-body">
                <form id="setQuestionForm">
                    <div class="form-group">
                        <label for="truthQuestion">Truth Question</label>
                        <input type="text" class="form-control" id="truthQuestion" name="truth" required>
                    </div>
                    <div class="form-group">
                        <label for="dareQuestion">Dare Question</label>
                        <input type="text" class="form-control" id="dareQuestion" name="dare" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Set Questions</button>
                </form>
            </div>
        </div>

        <div class="card" id="gameActionsCard" style="display: ;">
            <div class="card-header">
                Game Actions
            </div>
            <div class="card-body">
                <button id="startGameBtn" class="btn btn-success">Start Game</button>
                <button id="nextUserBtn" class="btn btn-warning">Next User</button>
            </div>
        </div>

        <div class="user-list" id="userList">
            <!-- User cards will be appended here -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-md5/2.18.0/js/md5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#registerForm').submit(function (e) {
                e.preventDefault();
                $.post('user.php', $(this).serialize() + '&register=true', function (data) {
                    alert(data);
                    $('#registerForm')[0].reset();
                });
            });

            $('#loginForm').submit(function (e) {
                e.preventDefault();
                $.post('user.php', $(this).serialize() + '&login=true', function (data) {
                    alert(data);
                    if (data === 'Logged in') {
                        $('#loginForm')[0].reset();
                        $('#hostGameCard').show();
                        $('#setQuestionCard').show();
                        $('#gameActionsCard').show();
                        loadUsers();
                    }
                });
            });

            $('#hostGameForm').submit(function (e) {
                e.preventDefault();
                $.post('user.php', $(this).serialize() + '&host=true', function (data) {
                    alert(data);
                    $('#hostGameForm')[0].reset();
                });
            });

            $('#setQuestionForm').submit(function (e) {
                e.preventDefault();
                $.post('game.php', $(this).serialize() + '&set_question=true', function (data) {
                    alert(data);
                    $('#setQuestionForm')[0].reset();
                });
            });

            $('#startGameBtn').click(function () {
                $.get('game.php?start=' + encodeURIComponent($('#roomName').val()), function (data) {
                    alert(data);
                });
            });

            $('#nextUserBtn').click(function () {
                $.get('game.php?next=true', function (data) {
                    alert(data);
                });
            });

            function loadUsers() {
                $.get('user.php?get_users=true', function (data) {
                    var users = JSON.parse(data);
                    $('#userList').empty();
                    users.forEach(function (user) {
                        var userCard = `
                            <div class="user-card">
                                <img src="https://www.gravatar.com/avatar/${md5(user.username)}?s=50&d=identicon" alt="${user.username}">
                                <div>
                                    <h5>${user.username}</h5>
                                </div>
                            </div>
                        `;
                        $('#userList').append(userCard);
                    });
                });
            }
        });
    </script>
</body>
</html>
