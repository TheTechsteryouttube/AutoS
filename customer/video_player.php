<?php
session_start();
include("../includes/db_connect.php");
$currentPage = '';
include("header.php");
if (!isset($_SESSION['user_id'])) die("Please login.");
$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) die("Invalid video.");
$video_id = intval($_GET['id']);

// Fetch video
$video = $con->query("SELECT * FROM videos WHERE id=$video_id")->fetch_assoc();
if (!$video) die("Video not found.");

// Likes & dislikes count
$likes = $con->query("SELECT COUNT(*) AS c FROM video_interactions WHERE video_id=$video_id AND type='like'")->fetch_assoc()['c'];
$dislikes = $con->query("SELECT COUNT(*) AS c FROM video_interactions WHERE video_id=$video_id AND type='dislike'")->fetch_assoc()['c'];

// Fetch comments
$comments = $con->query("
    SELECT vi.comment, vi.created_at, u.username 
    FROM video_interactions vi 
    JOIN users u ON vi.user_id=u.user_id
    WHERE vi.video_id=$video_id AND vi.type='comment'
    ORDER BY vi.created_at DESC
");

// Fetch related videos
$related = $con->query("
    SELECT id, title, filepath, thumbnail_path 
    FROM videos 
    WHERE id != $video_id 
    ORDER BY RAND() LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
  <title><?php echo htmlspecialchars($video['title']); ?> - AutoStream</title>
  <link rel="stylesheet" href="../assets/css/video_player.css">
</head>
<body>
<div class="container">
    <!-- Main video area -->
    <div class="video-section">
        <h2><?php echo htmlspecialchars($video['title']); ?></h2>
        <video controls>
            <source src="../<?php echo htmlspecialchars($video['filepath']); ?>" type="video/mp4">
        </video>

        <!-- Likes / Dislikes -->
        <div class="interaction-buttons">
            <button class="like-btn" data-action="like">👍 Like  <span id="like-count"><?php echo $likes; ?></span> </button>
            <button class="dislike-btn" data-action="dislike">👎 Dislike <span id="dislike-count"><?php echo $dislikes; ?></span></button>
        </div>

        <!-- Comments -->
        <div class="comments">
            <form id="comment-form">
                <textarea name="comment" required placeholder="Comments"></textarea>
                <button type="submit">Post Comment</button>
            </form>
            <div id="comments-list">
                <?php while ($c = $comments->fetch_assoc()): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($c['username']); ?></strong>: 
                        <?php echo htmlspecialchars($c['comment']); ?></p>
                        <small><?php echo $c['created_at']; ?></small>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Related videos -->
    <div class="related-section">
        <h3>Related Videos</h3>
        <?php while ($r = $related->fetch_assoc()): ?>
            <div class="related-video">
                <a href="video_player.php?id=<?php echo $r['id']; ?>">
                    <img src="../<?php echo htmlspecialchars($r['thumbnail_path']); ?>" alt="Thumbnail">
                    <p><?php echo htmlspecialchars($r['title']); ?></p>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
// Like & Dislike
document.querySelectorAll(".interaction-buttons button").forEach(btn => {
    btn.addEventListener("click", function() {
        let action = this.getAttribute("data-action");
        fetch("video_interact.php", {
            method: "POST",
            headers: {"Content-Type": "application/x-www-form-urlencoded"},
            body: "video_id=<?php echo $video_id; ?>&action=" + action
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("like-count").innerText = data.likes;
            document.getElementById("dislike-count").innerText = data.dislikes;
        });
    });
});

// Comment
document.getElementById("comment-form").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append("video_id", "<?php echo $video_id; ?>");
    formData.append("action", "comment");

    fetch("video_interact.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let newComment = document.createElement("div");
            newComment.classList.add("comment");
            newComment.innerHTML = `<p><strong>${data.username}</strong>: ${data.comment}</p>
                                    <small>${data.created_at}</small>`;
            document.getElementById("comments-list").prepend(newComment);
            this.reset();
        }
    });
});
</script>
</body>
</html>
