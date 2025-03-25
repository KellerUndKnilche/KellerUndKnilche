<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Registrieren</title>
</head>
<body>
    <div class="reg-container">
        <h2>Erstellen Sie ein Account</h2>
        <form id="registrationForm">
            <div class="mb-3">
             
                <input type="text" class="form-control" id="vorname" name="vorname" placeholder="Vorname" required>
            </div>
            <div class="mb-3">
              
                <input type="text" class="form-control" id="nachname" name="nachname" placeholder="Nachname" required>
            </div>
            <div class="mb-3">
               
                <input type="email" class="form-control" id="email" name="email" placeholder="E-Mail" required>
            </div>
            <div class="mb-3">
              
                <input type="text" class="form-control" id="username" name="username" placeholder="Benutzername" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password1" name="password" placeholder="Passwort" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password2" name="password" placeholder="Passwort wiederholen" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Registrieren</button>
            </div>
            <p class="text-danger mt-3 d-none" id="errorMessage">Bitte füllen Sie alle Felder aus.</p>
        </form>
    </div>
    <script src="./assets/js/script.js"></script>
</body>
</html>