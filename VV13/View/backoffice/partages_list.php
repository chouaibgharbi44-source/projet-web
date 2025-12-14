<h2>📁 Toutes les Ressources Partagées</h2>
<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>Matière</th>
            <th>Auteur</th>
            <th>Partagé par</th> <!-- user_id -->
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ressources as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['titre']) ?></td>
            <td><?= htmlspecialchars($r['nom_matiere']) ?></td>
            <td><?= htmlspecialchars($r['auteur']) ?></td>
            <td>Utilisateur <?= $r['user_id'] ?></td>
            <td><?= htmlspecialchars($r['date_ajout']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>