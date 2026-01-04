<?php
session_start();
require_once '../database/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'add_comment') {
    $match_id = intval($_POST['match_id']);
    $comment = $conn->real_escape_string($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    $conn->query("INSERT INTO comments (match_id, user_id, comment) VALUES ($match_id, $user_id, '$comment')");
    header("Location: user_home.php");
    exit();
}

// Get all matches
$matches = $conn->query("SELECT * FROM matches ORDER BY date_match DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - GoalPost</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-content">
            <h2>⚽ GoalPost</h2>
            <div class="nav-right">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../includes/auth.php?action=logout" class="btn-logout">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Upcoming & Live Matches</h1>

        <div class="matches-grid">
            <?php while ($match = $matches->fetch_assoc()): ?>
            <div class="match-card">
                <div class="match-header">
                    <span class="status <?php echo $match['status']; ?>"><?php echo ucfirst($match['status']); ?></span>
                    <span class="match-date"><?php echo date('M d, H:i', strtotime($match['date_match'])); ?></span>
                </div>

                <div class="match-teams">
                    <div class="team">
                        <h3><?php echo htmlspecialchars($match['team1']); ?></h3>
                        <?php if ($match['status'] == 'finished' || $match['status'] == 'live'): ?>
                            <p class="score"><?php echo htmlspecialchars($match['score_team1']); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="vs">VS</div>
                    <div class="team">
                        <h3><?php echo htmlspecialchars($match['team2']); ?></h3>
                        <?php if ($match['status'] == 'finished' || $match['status'] == 'live'): ?>
                            <p class="score"><?php echo htmlspecialchars($match['score_team2']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h4>Comments</h4>
                    <?php
                    $match_id = $match['id'];
                    $comments = $conn->query("SELECT c.*, u.username FROM comments c 
                                             JOIN users u ON c.user_id = u.id 
                                             WHERE c.match_id = $match_id 
                                             ORDER BY c.created_at DESC LIMIT 3");
                    
                    while ($comment = $comments->fetch_assoc()): ?>
                        <div class="comment">
                            <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                            <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                        </div>
                    <?php endwhile; ?>

                    <form method="POST" class="comment-form">
                        <input type="hidden" name="action" value="add_comment">
                        <input type="hidden" name="match_id" value="<?php echo $match['id']; ?>">
                        <input type="text" name="comment" placeholder="Add a comment..." required maxlength="500">
                        <button type="submit" class="btn-small">Post</button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="../assets/js/main.js"></script>
</body>
</html>