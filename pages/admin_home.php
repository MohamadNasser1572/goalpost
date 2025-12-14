<?php
session_start();
require_once '../database/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle match operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action == 'add_match') {
        $team1 = $conn->real_escape_string($_POST['team1']);
        $team2 = $conn->real_escape_string($_POST['team2']);
        $date = $conn->real_escape_string($_POST['date_match']);
        $user_id = $_SESSION['user_id'];

        $conn->query("INSERT INTO matches (team1, team2, date_match, status, created_by) 
                     VALUES ('$team1', '$team2', '$date', 'upcoming', $user_id)");
    }

    elseif ($action == 'update_score') {
        $match_id = intval($_POST['match_id']);
        $score1 = intval($_POST['score_team1']);
        $score2 = intval($_POST['score_team2']);
        $status = $conn->real_escape_string($_POST['status']);

        $conn->query("UPDATE matches SET score_team1 = $score1, score_team2 = $score2, status = '$status' 
                     WHERE id = $match_id");
    }

    elseif ($action == 'delete_match') {
        $match_id = intval($_POST['match_id']);
        $conn->query("DELETE FROM matches WHERE id = $match_id");
    }

    // Promote user to admin (admin-only)
    elseif ($action == 'promote_user') {
        $user_id = intval($_POST['user_id']);
        // Prevent self-demotion or redundant updates; just set role to admin
        $conn->query("UPDATE users SET role = 'admin' WHERE id = $user_id");
    }

    header("Location: admin_home.php");
    exit();
}

// Get all matches
$matches = $conn->query("SELECT * FROM matches ORDER BY date_match DESC");
// Get all regular users to allow promotion
$users = $conn->query("SELECT id, username, email, role FROM users WHERE role = 'user' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GoalPost</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <h2>⚽ GoalPost Admin</h2>
            <div class="nav-right">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../includes/auth.php?action=logout" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Match Management</h1>

        <!-- Add Match Form -->
        <div class="form-card">
            <h2>Add New Match</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add_match">
                <div class="form-row">
                    <input type="text" name="team1" placeholder="Team 1" required>
                    <input type="text" name="team2" placeholder="Team 2" required>
                    <input type="datetime-local" name="date_match" required>
                    <button type="submit" class="btn-submit">Add Match</button>
                </div>
            </form>
        </div>

        <!-- Matches Table -->
        <div class="table-card">
            <h2>All Matches</h2>
            <table class="matches-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Team 1</th>
                        <th>vs</th>
                        <th>Team 2</th>
                        <th>Date</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($match = $matches->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $match['id']; ?></td>
                        <td><?php echo htmlspecialchars($match['team1']); ?></td>
                        <td>vs</td>
                        <td><?php echo htmlspecialchars($match['team2']); ?></td>
                        <td><?php echo date('M d, H:i', strtotime($match['date_match'])); ?></td>
                        <td>
                            <?php 
                            if ($match['status'] == 'finished') {
                                echo $match['score_team1'] . ' - ' . $match['score_team2'];
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td><span class="status <?php echo $match['status']; ?>"><?php echo ucfirst($match['status']); ?></span></td>
                        <td>
                            <button class="btn-small" onclick="openEditModal(<?php echo $match['id']; ?>)">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete_match">
                                <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                                <button type="submit" class="btn-small btn-danger" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- User Management: Promote to Admin -->
        <div class="table-card" style="margin-top:24px;">
            <h2>Promote Users to Admin</h2>
            <p class="subtitle">Only visible to admins. Choose a user to promote.</p>
            <table class="matches-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($u = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><span class="status upcoming">User</span></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="promote_user">
                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                <button type="submit" class="btn-small">Promote to Admin</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Update Match Score</h2>
            <form id="editForm" method="POST">
                <input type="hidden" name="action" value="update_score">
                <input type="hidden" name="match_id" id="match_id">
                <input type="number" name="score_team1" id="score1" placeholder="Score Team 1" min="0" required>
                <input type="number" name="score_team2" id="score2" placeholder="Score Team 2" min="0" required>
                <select name="status" required>
                    <option value="upcoming">Upcoming</option>
                    <option value="live">Live</option>
                    <option value="finished">Finished</option>
                </select>
                <button type="submit" class="btn-submit">Update</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>
