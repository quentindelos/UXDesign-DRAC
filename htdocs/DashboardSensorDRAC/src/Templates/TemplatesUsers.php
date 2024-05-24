<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Liste des utilisateurs</h1>
        <form method="POST" action="AddUsers">
            <button class="btn btn-secondary mb-4">Ajouter un utilisateur</button>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Contrôle</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($users) > 0) {
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user["FIRSTNAME"]) . "</td>";
                        echo "<td>" . htmlspecialchars($user["LASTNAME"]) . "</td>";
                        echo "<td>" . htmlspecialchars($user["EMAIL"]) . "</td>";
                        echo "<td>" . htmlspecialchars($user["LIBELLE_TYPE_USER"]) . "</td>";
                        echo "<td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='user_id' value='" . htmlspecialchars($user["ID"]) . "'>
                                    <button type='submit' name='delete_user' class='btn btn-danger btn-sm'>Supprimer</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Aucun utilisateur trouvé</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>